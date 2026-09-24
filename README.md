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
- Backend admin — https://mfadhlan-portfolio-production.up.railway.app/admin

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
- **Uploads on object storage** — profile photo, CV file, and project
  images upload straight to Supabase Storage (S3-compatible) instead of
  the backend's local disk, so they survive redeploys on hosts with
  ephemeral filesystems (Railway).

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

File uploads (profile photo, CV, project images) go straight to the `s3`
disk (Supabase Storage) — set the `AWS_*` vars in `backend/.env` (see
`.env.example`) to a Supabase Storage bucket to test uploads locally; the
rest of the app works fine without them.

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

2. **Database & file storage → Supabase**
   - Create a Supabase project; grab the Postgres **Session pooler**
     connection details (Project Settings → Database) — host, port, user,
     database, password. Use the pooler host, not the direct `db.*.supabase.co`
     host, which is IPv6-only and unreachable from most PaaS hosts.
   - Create a **Storage bucket** (Storage → New bucket, mark it **Public**),
     then generate an S3 access key (Project Settings → Storage → S3
     Connection → New access key).

3. **Backend → Railway**
   - New service from the GitHub repo, root directory `backend`
   - Railway builds `backend/Dockerfile` (present in the repo) rather than
     `railway.json`'s declared Nixpacks builder — a `Dockerfile` in the root
     directory silently takes priority. Any new required PHP extension goes
     in the Dockerfile's `docker-php-ext-install` line, not left to
     auto-detection.
   - `railway.json`'s `startCommand` chains
     `migrate --force && storage:link --force && config:cache` before
     `php artisan serve` on boot — migrations run automatically on deploy
   - Required env vars: `APP_KEY`, `APP_ENV=production`, `APP_URL`,
     `CORS_ALLOWED_ORIGINS` (your Vercel URL), `ADMIN_EMAIL`/`ADMIN_PASSWORD`
     (used by the admin seeder — keep these set so re-seeding never resets
     the login), the `DB_*` vars from the Supabase pooler above (plus
     `DB_CONNECTION=pgsql` and `DB_SSLMODE=require`), and the `AWS_*` vars
     from the Supabase Storage bucket above (`AWS_ENDPOINT` is
     `https://<project-ref>.storage.supabase.co/storage/v1/s3`, `AWS_URL` is
     `https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>`,
     `AWS_USE_PATH_STYLE_ENDPOINT=true` — Supabase's S3 endpoint only
     supports path-style addressing)
   - Two production-only gotchas already handled in code, worth knowing about:
     `trustProxies(at: '*')` + `URL::forceScheme('https')` (Railway
     terminates TLS at the edge) and `User` implementing `FilamentUser`
     (Filament 403s any user that doesn't, outside `APP_ENV=local`)
   - Seed once manually after the first deploy:
     `railway ssh "php artisan db:seed --force"` — or run
     `php artisan migrate --seed` from a machine with the Supabase
     credentials in its local `.env`, which reaches the same database
     directly

4. **Frontend → Vercel**
   - Import the GitHub repo, root directory `frontend`
   - Build command `npm run build`, output directory `dist`
   - Set env var `VITE_API_URL` to `<backend-url>/api` — use the **Config**
     type, not **Secret**: `VITE_`-prefixed vars are inlined into the public
     JS bundle at build time regardless, so "Secret" only prevents you from
     viewing it again later, with no actual security benefit

5. **Wire them together**
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

Laravel 13 · Filament 3 · Supabase (Postgres + Storage) · React 19 · Vite · TypeScript · Tailwind CSS 4
