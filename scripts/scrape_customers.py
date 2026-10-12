#!/usr/bin/env python3
"""
Scrape ALL customer pages from panel user_portal.aspx.

Pager works like ASP.NET GridView:
  [1] 2 3 4 5 6 7 8 9 10 ...
Clicking "..." (Page$11) opens the next window:
  ... 12 13 14 15 16 17 18 19 20 ...
and so on until page 68.
"""

from __future__ import annotations

import json
import re
import sqlite3
import sys
import time
from datetime import datetime, timedelta
from html import unescape
from html.parser import HTMLParser
from pathlib import Path

import requests

ROOT = Path(__file__).resolve().parents[2]
SESSION_FILE = ROOT / "session.json"
LARAVEL_DB = ROOT / "laravel" / "database" / "database.sqlite"
OUT_JSON = ROOT / "laravel" / "storage" / "app" / "customers_scrape.json"
OUT_LOG = ROOT / "laravel" / "storage" / "app" / "customers_scrape.log"

URL = "https://panel123456.germanymode.com/page/user/user_portal.aspx"
GRID = "ctl00$ContentPlaceHolder1$GridView1"
SLEEP = 0.4
MAX_PAGES_GUARD = 80

SUPER_EMAILS = {
    "admin@germanymode.com",
    "h_joneidi1354@yahoo.com",
}


def log(msg: str) -> None:
    line = f"[{datetime.now():%H:%M:%S}] {msg}"
    print(line, flush=True)
    OUT_LOG.parent.mkdir(parents=True, exist_ok=True)
    with OUT_LOG.open("a", encoding="utf-8") as f:
        f.write(line + "\n")


def session_from_file() -> requests.Session:
    cookies = json.loads(SESSION_FILE.read_text(encoding="utf-8"))
    s = requests.Session()
    s.headers.update(
        {
            "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 "
            "(KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
            "Accept-Language": "fa-IR,fa;q=0.9,en;q=0.8",
            "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        }
    )
    for c in cookies:
        try:
            f"{c['name']}={c['value']}".encode("latin-1")
        except UnicodeEncodeError:
            continue
        s.cookies.set(
            c["name"],
            c["value"],
            domain=c.get("domain") or "panel123456.germanymode.com",
            path=c.get("path") or "/",
        )
    return s


def grab_hidden(html: str, name: str) -> str:
    m = re.search(rf'name="{re.escape(name)}"[^>]*value="([^"]*)"', html)
    if not m:
        m = re.search(rf'id="{re.escape(name)}"[^>]*value="([^"]*)"', html)
    return unescape(m.group(1)) if m else ""


def parse_pager(html: str) -> tuple[int | None, dict[int, str], str | None]:
    """
    Returns (current_page, numbered_links{page: Page$N}, forward_ellipsis_arg).
    Forward ellipsis is the "..." whose Page$N is greater than current.
    """
    current = None
    m = re.search(r'<tr class="pgr">[\s\S]*?<span>(\d+)</span>', html)
    if m:
        current = int(m.group(1))

    links: list[tuple[str, str]] = []
    for rx in (
        r"__doPostBack\('([^']+)','(Page\$[^']*)'\).*?>([^<]*)</a>",
        r"__doPostBack\(&#39;([^&]+)&#39;,&#39;(Page\$[^&]*)&#39;\).*?>([^<]*)</a>",
    ):
        for mm in re.finditer(rx, html):
            links.append((mm.group(2), unescape(mm.group(3)).strip()))

    numbered: dict[int, str] = {}
    forward_dot: str | None = None
    backward_dot: str | None = None
    for arg, text in links:
        num = int(arg.split("$", 1)[1])
        if text == "...":
            if current is None or num > current:
                # keep the farthest forward dots
                if forward_dot is None or num > int(forward_dot.split("$", 1)[1]):
                    forward_dot = arg
            else:
                backward_dot = arg
        elif text.isdigit():
            numbered[int(text)] = arg

    return current, numbered, forward_dot


class GridParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.in_grid = False
        self.in_tr = False
        self.in_cell = False
        self.depth = 0
        self.rows: list[list[str]] = []
        self.cur: list[str] | None = None
        self.buf: list[str] = []

    def handle_starttag(self, tag, attrs):
        attrs_d = dict(attrs)
        if tag == "table":
            tid = attrs_d.get("id", "")
            if tid.endswith("GridView1") or tid == "ContentPlaceHolder1_GridView1":
                self.in_grid = True
                self.depth = 1
            elif self.in_grid:
                self.depth += 1
        elif self.in_grid and self.depth == 1 and tag == "tr":
            self.in_tr = True
            self.cur = []
        elif self.in_tr and tag in ("td", "th"):
            self.in_cell = True
            self.buf = []

    def handle_endtag(self, tag):
        if tag == "table" and self.in_grid:
            self.depth -= 1
            if self.depth <= 0:
                self.in_grid = False
        elif tag == "tr" and self.in_tr:
            self.in_tr = False
            if self.cur is not None and any(c.strip() for c in self.cur):
                self.rows.append(self.cur)
            self.cur = None
        elif tag in ("td", "th") and self.in_cell:
            text = unescape(re.sub(r"\s+", " ", "".join(self.buf))).strip()
            if self.cur is not None:
                self.cur.append(text)
            self.in_cell = False

    def handle_data(self, data):
        if self.in_cell:
            self.buf.append(data)


def parse_users(html: str) -> list[dict]:
    p = GridParser()
    p.feed(html)
    users = []
    for row in p.rows:
        if not row or not re.fullmatch(r"\d+", row[0] or ""):
            continue
        if row[0] == "کدکاربری":
            continue
        portal_id = int(row[0])
        jalali = row[1] if len(row) > 1 else ""
        if not re.match(r"^\d{4}/\d{1,2}/\d{1,2}", jalali or ""):
            continue
        full = row[2] if len(row) > 2 else ""
        if not full or full.isdigit():
            continue
        email = (row[3] if len(row) > 3 else "").strip()
        mobile = (row[4] if len(row) > 4 else "").strip()
        parts = full.split(None, 1)
        users.append(
            {
                "portal_id": portal_id,
                "jalali_date": jalali,
                "name": parts[0] if parts else full,
                "last_name": parts[1] if len(parts) > 1 else "",
                "full_name": full,
                "email": email,
                "mobile": mobile,
            }
        )
    return users


def post_arg(s: requests.Session, html: str, arg: str) -> str:
    vs = grab_hidden(html, "__VIEWSTATE")
    vsg = grab_hidden(html, "__VIEWSTATEGENERATOR")
    ev = grab_hidden(html, "__EVENTVALIDATION")
    if not vs or not ev:
        raise RuntimeError(f"missing viewstate for {arg}")
    data = {
        "__EVENTTARGET": GRID,
        "__EVENTARGUMENT": arg,
        "__VIEWSTATE": vs,
        "__VIEWSTATEGENERATOR": vsg,
        "__EVENTVALIDATION": ev,
    }
    time.sleep(SLEEP)
    r = s.post(
        URL,
        data=data,
        timeout=90,
        headers={
            "Referer": URL,
            "Origin": "https://panel123456.germanymode.com",
            "Content-Type": "application/x-www-form-urlencoded",
        },
    )
    r.raise_for_status()
    if "GridView1" not in r.text or "کدکاربری" not in r.text:
        raise RuntimeError(f"bad response for {arg}: url={r.url} len={len(r.text)}")
    return r.text


def fetch_all(s: requests.Session) -> list[dict]:
    all_users: dict[int, dict] = {}
    visited_pages: set[int] = set()

    r = s.get(URL, timeout=90)
    r.raise_for_status()
    html = r.text
    if "GridView1" not in html:
        raise RuntimeError(f"session invalid: {r.url}")

    windows_without_forward = 0

    while len(visited_pages) < MAX_PAGES_GUARD:
        current, numbered, forward_dot = parse_pager(html)
        if current is None:
            raise RuntimeError("could not detect current page")

        # pages available in this pager window (current + numbered links)
        window_pages = sorted(set(numbered.keys()) | {current})
        log(
            f"window pages={window_pages[0]}..{window_pages[-1]} "
            f"current={current} forward_dot={forward_dot}"
        )

        for page in window_pages:
            if page in visited_pages:
                continue
            if page != current:
                # navigate within window
                arg = numbered.get(page) or f"Page${page}"
                try:
                    html = post_arg(s, html, arg)
                except Exception as e:
                    log(f"WARN navigate Page${page}: {e}")
                    # refresh and retry once
                    html = s.get(URL, timeout=90).text
                    # rebuild path by clicking dots until page reachable — skip for now
                    continue
                current, numbered, forward_dot = parse_pager(html)

            users = parse_users(html)
            for u in users:
                all_users[u["portal_id"]] = u
            visited_pages.add(page if current is None else (current or page))
            # if navigation landed elsewhere, mark actual current
            if current is not None:
                visited_pages.add(current)
            log(
                f"  scraped page {current or page}: "
                f"{len(users)} users (unique total={len(all_users)})"
            )

        if not forward_dot:
            windows_without_forward += 1
            log("no forward '...' — finished all pager windows")
            break

        # jump to next window via ...
        log(f"clicking forward '...' -> {forward_dot}")
        try:
            html = post_arg(s, html, forward_dot)
        except Exception as e:
            log(f"ERROR clicking ...: {e}")
            break

    return list(all_users.values())


def jalali_to_approx_dt(jalali: str) -> str | None:
    m = re.match(r"(\d{4})/(\d{1,2})/(\d{1,2})(?:\s+(\d{1,2}):(\d{1,2}))?", jalali or "")
    if not m:
        return None
    jy, jm, jd = int(m.group(1)), int(m.group(2)), int(m.group(3))
    hh, mm = int(m.group(4) or 0), int(m.group(5) or 0)
    try:
        gy = jy + 621
        day_of_year = (jm - 1) * 30 + jd
        base = datetime(gy, 3, 21) + timedelta(days=day_of_year - 1, hours=hh, minutes=mm)
        return base.strftime("%Y-%m-%d %H:%M:%S")
    except Exception:
        return None


def normalize_email(email: str, portal_id: int) -> str:
    email = (email or "").strip().lower()
    if not email or "@" not in email:
        return f"user{portal_id}@customers.local"
    if email.endswith("@gmail.co"):
        email += "m"
    if email.endswith("@yahoo.co"):
        email += "m"
    return email[:190]


def _email_taken(cur: sqlite3.Cursor, email: str, ignore_id: int | None) -> bool:
    if ignore_id is None:
        return cur.execute("SELECT 1 FROM users WHERE email = ? LIMIT 1", (email,)).fetchone() is not None
    return (
        cur.execute(
            "SELECT 1 FROM users WHERE email = ? AND id != ? LIMIT 1",
            (email, ignore_id),
        ).fetchone()
        is not None
    )


def upsert_laravel(users: list[dict]) -> tuple[int, int]:
    con = sqlite3.connect(LARAVEL_DB)
    cur = con.cursor()
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    inserted = updated = 0
    password = "$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"

    for u in users:
        portal_id = u["portal_id"]
        email = normalize_email(u.get("email", ""), portal_id)
        if email in SUPER_EMAILS:
            continue
        created = jalali_to_approx_dt(u.get("jalali_date", "")) or now

        row = cur.execute(
            "SELECT id, email, is_admin, role FROM users WHERE portal_id = ?",
            (portal_id,),
        ).fetchone()
        if row:
            uid, old_email, is_admin, role = row
            if is_admin or role in ("owner", "admin", "staff"):
                continue
            cur.execute(
                """
                UPDATE users SET
                    name=?, last_name=?, email=?, mobile=?,
                    role='customer', is_admin=0, is_blocked=0, updated_at=?
                WHERE id=?
                """,
                (
                    u.get("name") or "کاربر",
                    u.get("last_name") or "",
                    email if not _email_taken(cur, email, uid) else old_email,
                    u.get("mobile") or None,
                    now,
                    uid,
                ),
            )
            updated += 1
            continue

        if _email_taken(cur, email, None):
            email = f"user{portal_id}@customers.local"

        try:
            cur.execute(
                """
                INSERT INTO users (
                    portal_id, name, last_name, email, mobile, password,
                    is_admin, role, is_blocked, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, 0, 'customer', 0, ?, ?)
                """,
                (
                    portal_id,
                    u.get("name") or "کاربر",
                    u.get("last_name") or "",
                    email,
                    u.get("mobile") or None,
                    password,
                    created,
                    now,
                ),
            )
            inserted += 1
        except sqlite3.IntegrityError as e:
            log(f"skip portal_id={portal_id}: {e}")

    con.commit()
    con.close()
    return inserted, updated


def main() -> int:
    OUT_LOG.parent.mkdir(parents=True, exist_ok=True)
    OUT_LOG.write_text("", encoding="utf-8")
    log("starting customer scrape (pager windows via ...)")

    if not SESSION_FILE.exists():
        log(f"missing session: {SESSION_FILE}")
        return 1
    if not LARAVEL_DB.exists():
        log(f"missing db: {LARAVEL_DB}")
        return 1

    s = session_from_file()
    users = fetch_all(s)
    users.sort(key=lambda u: u["portal_id"], reverse=True)
    OUT_JSON.write_text(json.dumps(users, ensure_ascii=False, indent=2), encoding="utf-8")
    log(f"saved JSON: {OUT_JSON} ({len(users)} users)")

    inserted, updated = upsert_laravel(users)
    log(f"DB upsert done: inserted={inserted} updated={updated} total_scraped={len(users)}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
