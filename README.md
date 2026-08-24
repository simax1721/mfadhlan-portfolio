# M. Fadhlan — Portfolio

Personal portfolio site. Content (profile, skills, experience, projects,
education, organization) is stored in the database and managed through a
Filament admin panel — no code changes needed to update text, reorder
sections, or add a project.

```
backend/    Laravel 13 API + Filament admin panel (CMS), at /admin
frontend/   React + Vite + TypeScript + Tailwind (public site)
```

**Live:**
- Frontend — https://mfadhlan1721.vercel.app
- Backend admin — https://mfadhlan.up.railway.app/admin

## Features

- **CMS-driven content** — profile, skills (grouped by admin-managed
  categories), experience, projects, education, organization; all
  editable in Filament, no redeploy needed.
- **Bilingual (EN/ID)** — every text field has an `_en`/`_id` pair in the
  database; the frontend's language toggle switches instantly (`?lang=`
  query param on every API call), with English as the fallback.
- **Light/dark theme** — toggle in the navbar, persisted client-side.
- **Auto-generated CV PDF** — `GET /api/cv?lang=en|id` renders a PDF
  directly from the live CMS data (dompdf), so it's never out of sync
  with the site. Cached per locale and invalidated automatically when
  content changes.
- **Featured case study** — one project can be flagged `featured` in
  Filament to get an expanded case-study layout on the homepage instead
  of the standard project card.

## Local setup

**Backend**

```bash
cd backend
composer install
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

The seeder prints a generated admin password once — save it. Log in at
`http://localhost:8000/admin` with that email/password, or set
`ADMIN_EMAIL` / `ADMIN_PASSWORD` in `backend/.env` before seeding to pin
your own credentials.

**Frontend**

```bash
cd frontend
npm install
npm run dev
```

Opens at `http://localhost:5173`, fetching data from the backend API
(`VITE_API_URL` in `frontend/.env`, default `http://localhost:8000/api`).

## Editing content

Go to `/admin` on the backend and edit:
- **Profile** — name, summary, tagline, hero highlights, contact info, photo
- **Skill Categories** — add/reorder/rename the groups skills are shown under
- **Skills** — grouped by category, toggle "Core skill" to highlight it on the site
- **Experiences** — work history, drag to reorder
- **Projects** — title, description, tech stack, image, demo/GitHub links, `featured` flag
- **Education** / **Organization**

Changes appear on the public site immediately (no redeploy needed) since
the frontend reads live from the API. The CV PDF picks up changes on the
next download (its own cache is invalidated on save).

## Deployment

Code lives in one GitHub repo; frontend and backend deploy to separate
hosts.

1. **Push to GitHub** — `git push` to your remote; both platforms below
   auto-deploy on push to `main`.

2. **Backend → Railway**
   - New service from the GitHub repo, root directory `backend`
   - Add a **PostgreSQL** database plugin (MySQL works too, but Postgres
     avoids the `caching_sha2_password` auth headaches on managed MySQL 9)
   - `railway.json` pins the Nixpacks builder and chains
     `migrate --force && storage:link --force && config:cache` before
     `php artisan serve` on boot — migrations run automatically on deploy
   - Required env vars: `APP_KEY`, `APP_ENV=production`, `APP_URL`,
     `CORS_ALLOWED_ORIGINS` (your Vercel URL), `ADMIN_EMAIL`/`ADMIN_PASSWORD`
     (used by the admin seeder — keep these set so re-seeding never resets
     the login), plus the `DB_*` vars Railway injects from the Postgres plugin
   - Two production-only gotchas already handled in code, worth knowing about:
     `trustProxies(at: '*')` + `URL::forceScheme('https')` (Railway
     terminates TLS at the edge) and `User` implementing `FilamentUser`
     (Filament 403s any user that doesn't, outside `APP_ENV=local`)
   - Seed once manually after the first deploy:
     `railway ssh "php artisan db:seed --force"`

3. **Frontend → Vercel**
   - Import the GitHub repo, root directory `frontend`
   - Build command `npm run build`, output directory `dist`
   - Set env var `VITE_API_URL` to `<backend-url>/api`

4. **Wire them together**
   - Set `CORS_ALLOWED_ORIGINS` on the backend to the Vercel URL
   - Confirm the live frontend loads data from the live backend

## AI-assisted development

This project uses [Laravel Boost](https://laravel.com/docs/ai) for
backend work — an MCP server (`backend/.mcp.json`) exposing
Laravel-aware tools (`database-query`, `database-schema`, `search-docs`,
`browser-logs`, Tinker) plus curated guidelines/skills in
`backend/CLAUDE.md` and `backend/.claude/skills/`. It's a `--dev`
dependency, excluded from production builds.

## Tech stack

Laravel 13 · Filament 3 · PostgreSQL · React 19 · Vite · TypeScript · Tailwind CSS 4
