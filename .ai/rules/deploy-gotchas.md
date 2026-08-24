# Production deploy gotchas (Railway + Postgres)

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
