# Domain modules (modular monolith)

Each feature lives under `app/Domains/{Name}`:

| Domain | Owns |
|--------|------|
| Catalog | Products, categories, brands, warehouses, admin shop ops |
| Storefront | Public shop pages (home, category, product, search) |
| Orders | Cart, settlements, order admin stubs |
| Content | Blog, gallery, about, contact, quick links |
| Marketing | Banners, stories, mobile footer items |
| Media | Shared media library (`media` + `mediables`) and product galleries |
| SiteSettings | Site settings, short links, redirects, media registry, templates/plugins UI |
| Identity | Users, admin access levels, shop/admin auth |
| Interactions | Comments, visits, polls, tickets (+ admin RBAC UI for now) |
| AdminShell | Dashboard and generic admin page fallback |

Shared kernel code lives in `app/Shared` (base controller, media helper).

Models are only under each domain (`App\Domains\{Name}\Models\...`). There is no `app/Models` layer.

Web routes: `routes/web.php` auto-discovers every `app/Domains/*/Routes/web.php`.
