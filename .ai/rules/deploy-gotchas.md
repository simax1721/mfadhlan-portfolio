# Production deploy gotchas (Railway + Supabase)

- **Dockerfile silently overrides `railway.json`'s Nixpacks builder:**
  `railway.json` declares `"builder": "NIXPACKS"`, but Railway builds
  `backend/Dockerfile` instead whenever one exists in the service's root
  directory — the `builder` field is ignored, with no warning. Add any new
  required PHP extension to the Dockerfile's `docker-php-ext-install` line
  (currently `pdo_pgsql pgsql zip gd bcmath intl mbstring curl`); don't
  assume Nixpacks auto-detection is still in effect. This caused a real
  outage: `composer install` failed because `filament/support` requires
  `ext-intl`, which wasn't installed.
- **Database is Supabase Postgres, not a Railway plugin:** `DB_HOST` points
  at Supabase's Session pooler (`aws-0-<region>.pooler.supabase.com`, port
  `5432`), not the direct `db.<ref>.supabase.co` host — the direct host is
  IPv6-only and unreachable from Railway. Needs `DB_SSLMODE=require`.
- **File uploads go to Supabase Storage (S3), not local disk:** Railway's
  filesystem is ephemeral, so anything on the local `public` disk is lost on
  the next deploy/restart. The `s3` disk in `config/filesystems.php` is
  wired to Supabase Storage's S3-compatible API — needs `AWS_ENDPOINT`
  pointed at `https://<project-ref>.storage.supabase.co/storage/v1/s3` and
  `AWS_USE_PATH_STYLE_ENDPOINT=true` (Supabase's S3 endpoint only supports
  path-style addressing). Uploaded before this was set up? Those files are
  gone — re-upload through Filament once the disk is switched.
- **HTTPS behind the proxy:** `bootstrap/app.php` calls
  `trustProxies(at: '*')` and `AppServiceProvider::boot()` calls
  `URL::forceScheme('https')` in production. Railway terminates TLS at the
  edge and forwards plain HTTP — without these, generated asset URLs are
  `http://` on an `https://` page and get mixed-content-blocked. Don't
  remove either without re-testing the admin panel's CSS on the live URL.
- **Filament needs `FilamentUser`:** `User` implements
  `Filament\Models\Contracts\FilamentUser` (`canAccessPanel` returns `true`).
  Filament's own middleware 403s any user that doesn't implement it, but
  *only* outside `APP_ENV=local` — so this bug is invisible in local dev and
  only shows up in production.
- **`db:seed` is manual, `migrate` is not:** `railway.json`'s `startCommand`
  runs `migrate --force` on every boot, but never `db:seed`. After a schema
  change that also needs new/updated seed data in production, run it
  explicitly: `railway ssh "php artisan db:seed --force"`.
- **`ADMIN_PASSWORD` must stay set before re-seeding:** `AdminUserSeeder`
  does `User::updateOrCreate(['email' => ...], ['password' => bcrypt($password)])`.
  If `ADMIN_PASSWORD` isn't set in the environment, it generates a new
  random password *every time the seeder runs* — silently resetting the
  admin login. Confirm it's set in Railway's variables before running
  `db:seed` against production.
- **Full `db:seed` overwrites CMS edits:** `PortfolioSeeder` uses
  `updateOrCreate` keyed on stable identifiers, so it will overwrite any
  field a real admin edit changed in Filament (tagline, bio, etc.) back to
  the seeder's hardcoded value. Prefer a narrow, targeted update/migration
  over a full re-seed once the site has real production content that might
  have been hand-edited.
