# Changelog

Chronological record of major work on this project, grouped by
feature/milestone rather than by individual commit. For day-to-day commit
history see `git log`; for in-progress work see `.ai/redesign/plan.md`.

## 2026-08-20 — Initial build & production deploy

**Core site**
- Built the portfolio from the ground up: Laravel 13 + Filament 3 API/CMS
  (`backend/`) and a React 19 + Vite + TypeScript + Tailwind CSS 4 SPA
  (`frontend/`), content grounded in real CV/project details rather than
  placeholder copy.
- CMS-driven content — profile, skills, experience, projects, education,
  organization all editable in Filament, nothing hardcoded in the frontend.
- Bilingual EN/ID support (`_en`/`_id` column pairs, `HasLocalizedFields`
  trait, `SetApiLocale` middleware, `LocaleContext` on the frontend) and a
  light/dark theme toggle.
- Auto-generated CV PDF (`barryvdh/laravel-dompdf`), replacing a stale
  static upload — rendered from the same live CMS data as the site, cached
  and invalidated per locale.

**Deployment**
- Initialized git, pushed to GitHub (`simax1721/mfadhlan-portfolio`, public).
- Deployed backend to **Railway** and frontend to **Vercel**. Evaluated
  Railway+MySQL, then Render, before settling on **Railway + PostgreSQL**
  (MySQL 9's `caching_sha2_password` auth had no clean fix without SSL or a
  disabled default plugin; Postgres had no such issue).
- Fixed two production-only bugs found via live debugging: mixed-content
  asset blocking (`trustProxies` + `URL::forceScheme('https')`, since
  Railway terminates TLS at the edge) and a 403 on the Filament admin login
  (`User` needed to implement `FilamentUser`, a check Filament only enforces
  outside `APP_ENV=local`).

**Skill Categories CRUD**
- Replaced the hardcoded skill-category grouping with a proper
  `SkillCategory` model + Filament resource, with a data migration
  backfilling the old flat `skills.category` strings into the new table.

## 2026-08-23 — Featured case study, hero highlights, SEO polish

Worked through `frontend/FRONTEND_IMPROVEMENT_PLAN.md`'s P1–P3 backlog
(P0 — real project screenshots — deferred by request):

- **P1:** `FeaturedProject.tsx` (two-column case-study layout: challenge /
  what-was-built / result) for the project flagged `featured`; Hero gained
  an evidence-line of `profile.highlights` and reordered CTAs.
- **P2:** `Experience` bullets collapse to 3 with a show more/less toggle;
  core skills visually highlighted (`Skill.highlighted`); renamed the
  "AI-Assisted Development" skill category to "Engineering Workflow" (via a
  proper data migration, not a seeder-key rename, to avoid duplicating the
  row); `Navbar` scroll-spy + `aria-expanded`/Escape-to-close; added a
  `BackToTop` button and a global `:focus-visible` ring.
- **P3:** canonical URL, Open Graph + Twitter Card meta tags, JSON-LD
  `Person` schema, and synced the static `lang` attribute to the real
  default locale.
- This batch was briefly pushed, reverted at the user's request (a push
  landed before they could review it), then reapplied and kept local until
  explicit confirmation to push again.

## 2026-08-24 — Tooling, documentation, and a design review

- Installed [Laravel Boost](https://laravel.com/docs/ai) (`backend/`,
  dev-only) — an MCP server plus Laravel-specific guidelines/skills for AI
  coding agents.
- Re-seeded the production database for the 2026-08-23 batch's new fields
  (`profile.highlights`, `skill.highlighted`), after confirming
  `ADMIN_PASSWORD` was set so the admin login wouldn't reset.
- Rewrote `README.md`, `backend/README.md`, and `frontend/README.md`, which
  had gone stale (wrong Laravel version, missing i18n/theme/CV-PDF
  features, generic deploy steps not matching the actual Railway+Postgres
  setup).
- Installed the `ui-ux-pro-max` design skill set (`.claude/skills/`, run by
  the user via its own CLI) and used it to review the homepage: 7 findings
  logged in `.ai/redesign/review-homepage.md` (2 critical — mobile nav
  touch-target, missing skip-link; 1 high — declared but unloaded web
  fonts; 2 medium; 2 minor), with a tracked fix plan in
  `.ai/redesign/plan.md` and rationale in `.ai/redesign/decisions.md`.
- Added a root `CLAUDE.md` (architecture overview + commands for future
  sessions) and `.ai/rules/` (durable, area-scoped technical rules:
  bilingual content, cache invalidation, deploy gotchas, frontend styling,
  git workflow).
- **Critical bug fix — CV PDF Technical Skills section rendered raw JSON:**
  `CvController` grouped `Skill::get()` by `'category'`, but `category()`
  is a `belongsTo` relation to `SkillCategory`, not a string column; used
  as a `groupBy` array key it got stringified via Eloquent's `__toString()`
  (which returns `toJson()`), so every category heading in the downloaded
  CV showed the category's raw JSON instead of its name. Fixed by querying
  `SkillCategory::with('skills')` instead, matching the pattern
  `BootstrapController` already used correctly for the live site.
- Simplified the section-background pass from `.ai/redesign/plan.md`'s
  B1–B5 (which had grown into 6 different per-section dot-grid/blob
  configurations) down to one fixed look on alternating sections; fixed a
  blob-positioning bug that rendered as a hard-edged wall instead of a
  soft glow on wide viewports, and a second missing-`isolate` visibility
  bug (same root cause as B4, in the loading screen this time). Restructured
  `FeaturedProject` from a 2-column grid to a stacked layout matching the
  other project cards (its old layout stretch-cropped the image), and added
  a "show more" toggle to the project grid ahead of more projects being
  added later. Full detail in `.ai/redesign/plan.md`.

## 2026-08-25 — Persistent uploads, first push since the redesign, loader timing

- **Infra fix — profile/project photo uploads were being wiped on every
  Railway deploy:** Railway service containers are ephemeral (a fresh
  filesystem on each deploy), and the `mfadhlan-portfolio` backend service
  had no persistent volume — only the Postgres database did. Any file
  uploaded at runtime via Filament (`storage/app/public/...`) lived only on
  that deploy's container and vanished on the next one. Fixed by attaching
  a Railway Volume (`mfadhlan-portfolio-volume`, 500MB) mounted at
  `/app/storage/app/public` (confirmed via `railway ssh` — real `ext4`
  mount, writable). Uploads made after this point persist across deploys;
  anything uploaded before it (the profile photo, and the P1 project
  images below) had to be re-uploaded once.
- Re-uploaded the profile photo and all 4 project images via the
  production Filament admin — both had been wiped by the issue above
  before the volume existed. This also means `.ai/redesign/plan.md`'s P1
  (real project screenshots) and P2 (skill-category order, via its
  migration) are now genuinely live in production, not just local —
  their rows were stale until this entry.
- **First `git push` since the redesign pass began** (2026-08-24 pass
  through this entry, 7 commits): background simplification + bug fixes,
  the CV PDF JSON-leak fix, the Featured Project rework, and this entry's
  volume/upload fixes. Verified live post-deploy: profile photo loads,
  CV PDF's Technical Skills section shows real category names (checked via
  `pdftotext` against the production PDF — zero JSON-shaped matches).
- Loading screen's terminal typewriter was getting cut off mid-line on the
  live site — `/api/bootstrap` now resolves fast enough (cache hit) to
  beat the animation, which doesn't happen locally against an uncached
  `php artisan serve`. Added a 1200ms minimum display time for the loader,
  gated to production builds only (`import.meta.env.PROD`) so local dev
  stays instant.

## 2026-09-25 — Railway usage limit forced a migration to a new project + Supabase

- **The original Railway account hit its usage limit**, blocking further
  deploys to the `mfadhlan.up.railway.app` backend. Evaluated Render as a
  replacement (Docker-only builder, no free Nixpacks-style PHP buildpack)
  but its signup flow demanded card verification; same story on Koyeb,
  whose free Starter tier has effectively been discontinued for new
  accounts post-acquisition. Landed on **a second, separate free Railway
  account** for compute, keeping Railway's git-push-to-deploy workflow, but
  moved both the database and file storage off Railway itself so neither is
  tied to whichever compute host is used going forward.
- **Database moved to Supabase Postgres.** Connected via the Session
  pooler (`aws-0-ap-northeast-1.pooler.supabase.com:5432`), not the direct
  `db.<ref>.supabase.co` host — the direct host is IPv6-only and
  unreachable from most PaaS hosts. Ran `migrate --force` and `db:seed
  --force` directly from a local machine with the Supabase credentials in
  `.env` (needed enabling the `pdo_pgsql`/`pgsql` PHP extensions in the
  local Laragon `php.ini`, which ship disabled by default).
- **`backend/Dockerfile` was added for the (ultimately abandoned) Render
  attempt, but it broke the first deploy on the new Railway project**:
  Railway silently builds from a `Dockerfile` when one exists in the
  service root, overriding `railway.json`'s declared `"builder": "NIXPACKS"`
  with no warning. `composer install` failed because `filament/support`
  requires `ext-intl`, which the Dockerfile didn't install — fixed by
  adding `intl`, `mbstring`, and `curl` to its `docker-php-ext-install`
  line. See `.ai/rules/deploy-gotchas.md`.
- **File uploads (profile photo, CV, project images) moved to Supabase
  Storage**, an S3-compatible object store, via a new `s3` disk
  (`config/filesystems.php`, already present but unused) — added
  `league/flysystem-aws-s3-v3`, pointed the three Filament `FileUpload`
  fields and their matching model URL accessors (`Profile.php`,
  `Project.php`) at it. This is the same ephemeral-filesystem problem the
  2026-08-25 entry fixed with a Railway Volume — that volume didn't carry
  over to the new Railway project, so this time storage was decoupled from
  the compute host entirely instead of re-attaching another volume.
  Confirmed working with a real upload + public-URL fetch before rolling
  it out; the profile photo, CV, and project images uploaded before this
  point were unrecoverable (they lived only on the old Railway container)
  and had to be re-uploaded through Filament afterward.
- Fixed a Vercel config mistake found along the way: `VITE_API_URL` was set
  as a **Secret** env var, which doesn't apply to `VITE_`-prefixed vars —
  Vite inlines them into the public JS bundle at build time regardless, so
  "Secret" only blocks viewing the value again later, with no actual
  privacy benefit. Switched to **Config**.
- Vendored two more skills into `backend/.claude/skills/`: `supabase` and
  `supabase-postgres-best-practices`.

## Status as of this entry

Redesign is ongoing, not finished — full detail in `.ai/redesign/plan.md`.
Completed so far: the original 7-item review, positioning fixes (real
project images, skill-category order, section order), Hero visual pass
(terminal mockup, typewriter, icons extended to Contact/Footer), the
loading screen restyle and later min-delay fix, animation polish (stagger,
BackToTop/mobile-menu transitions), a mobile nav-scroll + spacing fix,
section backgrounds and their later simplification/bug-fix pass, a
Featured Project layout rework, and a persistent-storage fix for Railway
uploads (superseded 2026-09-25 by the move to Supabase Storage, see below).
Each round has been started by a new instruction from Fadhlan (the user)
rather than a fixed backlog — expect more. Work is committed locally as it
lands and pushed only on Fadhlan's explicit go-ahead each time (see
`.ai/rules/git-workflow.md`) — not on a fixed schedule; pushes so far:
2026-08-25 and three more during the 2026-09-25 Railway/Supabase migration.

Separately from the redesign, 2026-09-25 replaced the deploy infrastructure
itself (new Railway project, Supabase for Postgres and Storage) after the
original Railway account hit its usage limit — see that entry above for
detail.
