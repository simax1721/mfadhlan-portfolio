# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A personal portfolio site. `backend/` is a Laravel 13 + Filament 3 API/CMS;
`frontend/` is a React 19 + Vite + TypeScript + Tailwind CSS 4 SPA that reads
all its content live from the backend — no hardcoded copy. They deploy to
separate hosts (Railway for the backend + Postgres, Vercel for the frontend)
and are developed/run independently. See [README.md](README.md) for the full
feature list and deployment steps, [CHANGELOG.md](CHANGELOG.md) for a
chronological record of major work, `.ai/rules/index.md` for durable,
area-scoped technical rules, and `.ai/redesign/` for in-progress redesign
tracking.

## Commands

**Backend** (run from `backend/`):
```bash
composer install
php artisan migrate --seed   # seeder prints a generated admin password once
php artisan serve
php artisan test --compact                        # full suite
php artisan test --compact --filter=testName       # single test
vendor/bin/pint --dirty --format agent             # format PHP after edits
php -l path/to/File.php                            # quick syntax check
```

**Frontend** (run from `frontend/`):
```bash
npm install
npm run dev       # dev server, expects backend at http://localhost:8000
npm run build     # tsc -b && vite build — type-check + production build
npm run lint      # oxlint
npm run preview
```

The frontend needs the backend running to show real data (`VITE_API_URL` in
`frontend/.env`, defaults to `http://localhost:8000/api`).

## Architecture

### Bilingual content spans both apps

Every translatable field is stored as a column pair (`summary_en` /
`summary_id`, etc.). Backend: `HasLocalizedFields::trans('field')`
(`app/Models/Concerns/`) resolves the active locale with an English
fallback; the active locale comes from `?lang=` on every API route, read by
the `SetApiLocale` middleware. Frontend: `LocaleContext`
(`src/i18n/LocaleContext.tsx` + `src/i18n/useLocale.ts`, split into a
provider file and a hook file specifically to satisfy the
`react(only-export-components)` Fast Refresh lint rule) drives that same
`?lang=` param on every API call and refetches on change. UI chrome strings
(buttons, labels) live in `frontend/src/i18n/locales/{en,id}.ts`; all other
text comes from the API already resolved to the active locale — there's no
separate frontend translation layer for content.

### One bootstrap request drives the whole page

`GET /api/bootstrap?lang=` (`BootstrapController`) aggregates profile,
skills, experiences, projects, education, and organizations into one
payload. The frontend fetches it once in `App.tsx` via `useFetch`; changing
the language toggle refetches with the new `lang`. Individual per-resource
endpoints also exist (`/api/skills`, `/api/projects`, etc.) but the frontend
doesn't use them — they're there for direct/CMS-side use.

### Cache invalidation is automatic on save

`/api/bootstrap` and `/api/cv` are cached per locale
(`portfolio.bootstrap.{en,id}`, `portfolio.cv.{en,id}`) because per-request
PHP bootstrap cost dominates response time in this environment. Every model
uses `InvalidatesPortfolioCache` (`app/Models/Concerns/`), which clears the
relevant keys on save/delete — this is why editing content in Filament
appears on the live site immediately, no manual `cache:clear` needed.

### CV PDF is generated, not uploaded

`GET /api/cv?lang=` (`CvController`) renders `resources/views/cv/pdf.blade.php`
via `barryvdh/laravel-dompdf` from the same CMS data as the site, so it can
never go stale. Cached and invalidated the same way as bootstrap.

### Skills are grouped by an admin-managed category

`SkillCategory` (not a hardcoded enum) owns `Skill`s and controls their
display order and grouping on the site; both category and individual skill
have a `highlighted` boolean used by the frontend to visually emphasize
"core" skills/categories.

### Tailwind v4 is CSS-first — no `tailwind.config.js`

Theme tokens live in `frontend/src/index.css` under `@theme`. Light mode is
a `:root[data-theme="light"]` override block sitting *outside* Tailwind's
generated `@layer theme`, so its unlayered cascade wins without needing
`!important` — except for `box-shadow`, where Tailwind's own reset
initializes a shadow stack on every element and does need `!important` to
override (see the comments in `index.css` around `.bg-surface`/`.btn-*`).

### Production deploy has three non-obvious fixes baked in

Backend `AppServiceProvider`/`bootstrap/app.php` call `trustProxies(at: '*')`
+ `URL::forceScheme('https')` because Railway terminates TLS at the edge and
forwards plain HTTP (otherwise assets mixed-content-block). `User` implements
`FilamentUser` because Filament 403s any user that doesn't, outside
`APP_ENV=local`. `railway.json`'s `startCommand` chains
`migrate --force && storage:link --force && config:cache` on every boot —
migrations run automatically on deploy, but **`db:seed` does not** and must
be run manually (`railway ssh "php artisan db:seed --force"`) after schema
changes that also need new seed data.

### AI tooling installed in this repo

`backend/` has [Laravel Boost](https://laravel.com/docs/ai) (`--dev` only,
excluded from production builds) — an MCP server plus guidelines/skills for
backend work. The repo root has a `ui-ux-pro-max` skill set
(`.claude/skills/`) for UI/UX design review and implementation guidance,
installed via a third-party CLI. Both are advisory tooling, not authoritative
over user instructions or this file.

### `git push` requires explicit go-ahead

Commit locally freely, but never `git push` without the user confirming in
that turn — even after lint/build/tests pass — because it triggers an
auto-deploy on both Railway and Vercel. See
[.ai/rules/git-workflow.md](.ai/rules/git-workflow.md).
