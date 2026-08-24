# Backend — M. Fadhlan Portfolio

Laravel 13 API + Filament 3 admin panel. Serves the public portfolio's
content as JSON and generates the downloadable CV PDF, both from the
same CMS data edited at `/admin`.

See the [repo root README](../README.md) for the full project overview,
local setup for both apps, and deployment notes.

## Structure

```
app/Models/              Profile, Skill, SkillCategory, Experience, Project,
                          EducationEntry, OrganizationEntry, User
app/Models/Concerns/      HasLocalizedFields, InvalidatesPortfolioCache traits
app/Http/Controllers/Api/ One controller per resource + BootstrapController
                          (aggregates everything in one request) + CvController
app/Http/Resources/       JSON shape per model, locale-resolved via ->trans()
app/Filament/Resources/   Admin CRUD for each model
app/Filament/Pages/       ManageProfile (singleton profile editor)
app/Http/Middleware/      SetApiLocale (reads ?lang=, defaults to en)
```

## Bilingual content

Translatable fields are stored as column pairs (`summary_en` /
`summary_id`, etc.). `HasLocalizedFields::trans('field')` resolves the
active locale with an English fallback. The active locale comes from the
`?lang=` query param on every API route (`SetApiLocale` middleware).

## Caching

`GET /api/bootstrap` and `GET /api/cv` are cached per locale
(`portfolio.bootstrap.{en,id}`, `portfolio.cv.{en,id}`) since PHP's
per-request bootstrap cost dominates response time here. Every model
uses the `InvalidatesPortfolioCache` trait, so saving anything in
Filament clears the relevant cache keys immediately — no manual
`cache:clear` needed after editing content.

## Key routes

```
GET  /api/bootstrap?lang=en|id   Combined payload the frontend loads on page open
GET  /api/cv?lang=en|id          Auto-generated CV PDF (dompdf, from live CMS data)
GET  /api/{profile,skills,experiences,projects,education,organizations}
                                  Individual resource endpoints
/admin                            Filament admin panel
```

## Local setup

```bash
composer install
cp .env.example .env       # if starting fresh
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

The seeder ([`AdminUserSeeder`](database/seeders/AdminUserSeeder.php))
prints a generated admin password once unless `ADMIN_EMAIL` /
`ADMIN_PASSWORD` are set in `.env` beforehand — set them if you plan to
reseed later, since re-running the seeder without them generates a new
random password each time.

## Testing

```bash
php artisan test --compact
```

## AI-assisted development

This app has [Laravel Boost](https://laravel.com/docs/ai) installed
(`--dev` only, excluded from production builds). It registers an MCP
server (`.mcp.json`, runs `php artisan boost:mcp`) giving AI coding
agents Laravel-aware tools — `database-query`, `database-schema`,
`search-docs`, `browser-logs` — plus guidelines in `CLAUDE.md` and
skills in `.claude/skills/` (`laravel-best-practices`,
`infer-conventions`).

## Tech stack

Laravel 13 · PHP 8.4 · Filament 3 · barryvdh/laravel-dompdf · PostgreSQL (production) / SQLite (local)
