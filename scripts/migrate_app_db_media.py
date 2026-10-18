#!/usr/bin/env python3
"""Migrate gallery_urls from products into media + mediables in app.db."""

from __future__ import annotations

import json
import os
import sqlite3
from pathlib import Path
from urllib.parse import urlparse

ROOT = Path(__file__).resolve().parents[2]
DB_PATH = ROOT / "app.db"


def decode_gallery(raw: str | None) -> list[str]:
    if not raw:
        return []
    try:
        data = json.loads(raw)
    except Exception:
        return []
    if not isinstance(data, list):
        return []
    return [str(p).strip() for p in data if str(p).strip()]


def main() -> None:
    if not DB_PATH.is_file():
        raise SystemExit(f"Missing {DB_PATH}")

    conn = sqlite3.connect(DB_PATH)
    conn.execute("PRAGMA foreign_keys = ON")
    cur = conn.cursor()

    cur.execute(
        """
        CREATE TABLE IF NOT EXISTS media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            path TEXT NOT NULL UNIQUE
        )
        """
    )
    cur.execute(
        """
        CREATE TABLE IF NOT EXISTS mediables (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            media_id INTEGER NOT NULL,
            model_id INTEGER NOT NULL,
            model_type TEXT NOT NULL,
            FOREIGN KEY(media_id) REFERENCES media(id) ON DELETE CASCADE
        )
        """
    )
    cur.execute(
        "CREATE INDEX IF NOT EXISTS mediables_model_type_model_id_index ON mediables (model_type, model_id)"
    )

    cols = {row[1] for row in cur.execute("PRAGMA table_info(products)")}
    if "gallery_urls" not in cols:
        print("gallery_urls already removed from app.db products")
        conn.close()
        return

    # Fresh rebuild of media relations from gallery_urls
    cur.execute("DELETE FROM mediables")
    cur.execute("DELETE FROM media")

    media_by_path: dict[str, int] = {}
    mediable_rows = 0

    for product_id, gallery_urls in cur.execute("SELECT id, gallery_urls FROM products ORDER BY id"):
        for path in decode_gallery(gallery_urls):
            if path not in media_by_path:
                parsed = urlparse(path)
                title = os.path.basename(parsed.path or path) or "media"
                cur.execute("INSERT INTO media (title, path) VALUES (?, ?)", (title, path))
                media_by_path[path] = int(cur.lastrowid)
            cur.execute(
                "INSERT INTO mediables (media_id, model_id, model_type) VALUES (?, ?, ?)",
                (media_by_path[path], product_id, "product"),
            )
            mediable_rows += 1

    cur.execute("ALTER TABLE products DROP COLUMN gallery_urls")
    conn.commit()

    media_count = cur.execute("SELECT COUNT(*) FROM media").fetchone()[0]
    print(f"app.db media={media_count} mediables={mediable_rows}; dropped products.gallery_urls")
    conn.close()


if __name__ == "__main__":
    main()
