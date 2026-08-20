# M. Fadhlan — Portfolio

Personal portfolio site. Content (profile, skills, experience, projects,
education, organization) is stored in the database and managed through a
Filament admin panel — no code changes needed to update text or add a
project.

```
backend/    Laravel 11 API + Filament admin panel (CMS), at /admin
frontend/   React + Vite + TypeScript + Tailwind (public site)
```

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
- **Profile** — name, summary, contact info, photo, CV file
- **Skills** — grouped by Backend / Frontend / Database / Tools
- **Experiences** — work history, drag to reorder
- **Projects** — title, description, tech stack, image, demo/GitHub links, featured flag
- **Education** / **Organization**

Changes appear on the public site immediately (no redeploy needed) since
the frontend reads live from the API.

## Deployment

Code lives in one GitHub repo; frontend and backend deploy to separate
hosts.

1. **Push to GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial portfolio"
   git remote add origin <your-repo-url>
   git push -u origin main
   ```

2. **Backend → Railway or Render**
   - New project from the GitHub repo, root directory `backend`
   - Add a MySQL/PostgreSQL database add-on, set `DB_*` env vars accordingly
   - Set `APP_KEY` (generate with `php artisan key:generate --show`), `APP_URL`, `CORS_ALLOWED_ORIGINS` (your frontend's deployed URL)
   - Run `php artisan migrate --seed` and `php artisan storage:link` as a release/deploy step
   - Note the deployed backend URL (e.g. `https://your-api.up.railway.app`)

3. **Frontend → Vercel**
   - Import the GitHub repo, root directory `frontend`
   - Build command `npm run build`, output directory `dist`
   - Set env var `VITE_API_URL` to `<backend-url>/api`
   - Deploy, note the deployed frontend URL

4. **Wire them together**
   - Update `CORS_ALLOWED_ORIGINS` on the backend to the Vercel URL and redeploy
   - Confirm the live frontend loads data from the live backend

## Tech stack

Laravel 11 · Filament 3 · React 19 · Vite · TypeScript · Tailwind CSS 4
