# Redesign Execution Plan

Tracks the work coming out of [review-homepage.md](review-homepage.md).
Update the Status column as items land — don't delete finished rows, so this
stays a record of what changed and why.

**Process note:** per user instruction, no `git push` during this redesign —
commit locally as work lands, push only when explicitly asked.

## Homepage fixes (from review-homepage.md)

| # | Finding | Severity | File(s) | Status |
|---|---------|----------|---------|--------|
| 1 | Mobile nav hamburger hit-area (add `p-2`) | 🔴 Critical | `Navbar.tsx` | Done — verified 40×40px hit area at 375px width |
| 2 | No skip-link | 🔴 Critical | `App.tsx`, `i18n/locales/{en,id}.ts` | Done — verified via real Tab keypress, `position:fixed` + visible on focus |
| 3 | Inter/JetBrains Mono declared but never loaded | 🟠 High | `index.html` | Done — Google Fonts link added, verified via `document.fonts` (all weights `loaded`) |
| 4 | `text-dim` contrast borderline in light mode | 🟡 Medium | `index.css` (`--color-text-dim` light) | Done — `#64748b`→`#5b6b82`, contrast ~4.5:1→~5.1:1 on `--color-bg` |
| 5 | `LanguageToggle` tap size | 🟡 Medium | `LanguageToggle.tsx` | Done — `px-2.5 py-1`→`px-3 py-1.5`, verified 38×28px |
| 6 | Icon stroke-width consistency (1.75 vs 2) | 🟢 Minor | `BackToTop.tsx` | Done — standardized on 1.75 (majority value across `Navbar`/`ThemeToggle`) |
| 7 | More descriptive alt text | 🟢 Minor | `Projects.tsx`, `FeaturedProject.tsx`, `i18n/locales/{en,id}.ts` | Done — bare `project.title` → localized `t("projects.previewAlt", {title})` ("Preview of the {title} project" / "Pratinjau project {title}"), verified both locales render correctly on all 4 cards |

## Positioning/visual-impact follow-up (2026-08-24)

User re-review: "does this look like a backend/fullstack dev, and would a
recruiter find it appealing?" — findings not in the original accessibility
review, tracked separately.

| # | Finding | Priority | File(s) | Status |
|---|---------|----------|---------|--------|
| P1 | All 4 projects have `image_url: null` — every card (incl. the Featured Case Study) shows a random unrelated picsum.photos stock photo | 🔴 Highest-impact | data (`projects.image_url`) | **Done (local)** — Aceh Cinema & Amanah Aceh use real screenshots of their live sites (cropped 16:9, user captured + ffmpeg crop). HydroSmart IoT & Berkah Bibit use branded SVG placeholders (dark/cyan theme, honest "Preview coming soon" tag) since they have no live demo. All 4 uploaded via Filament, verified live at localhost:5173. **Not yet applied to production.** |
| P2 | Skill category order led with "Engineering Workflow" (AI tooling) ahead of "Backend" — undercut the site's own positioning | 🟠 High | `PortfolioSeeder.php`, new migration `2026_08_24_090000_reorder_skill_categories_backend_first.php` | Done — reordered Backend→Frontend→Database→Tools→Engineering Workflow, verified locally, **not yet applied to production** (needs `railway ssh` migrate or wait for next deploy) |
| P3 | Section order puts Projects (strongest proof-of-work) after Hero→About→Skills→Experience, later than the `portfolio-grid` pattern's Hero→Projects→About | 🟡 Discuss | `App.tsx`, `Navbar.tsx` (section + nav order) | Done — discussed 3 options (full reorder / partial / leave as-is), chose partial: Hero→About→**Projects**→Skills→Experience→Education→Contact. Nav links + scroll-spy `SECTION_IDS` reordered to match. Verified locally (lint, build, live render, nav order) |

## Suggested order

1. ~~Items 1–2 (Critical, accessibility) — small, isolated, low-risk changes.~~ Done.
2. ~~Item 3 (High, font loading) — see [decisions.md](decisions.md).~~ Done.
3. ~~Items 4–5 (Medium) — quick token/spacing tweaks.~~ Done.
4. ~~Item 6 (Minor) — icon stroke-width consistency.~~ Done.
5. Item 7 — stays blocked until P0 (real project screenshots) is picked up.

## Verification per item

Same pattern used earlier this session: `npm run lint`, `npm run build`,
then a browser check (relevant breakpoint / theme / keyboard nav for the
specific fix) before marking a row Done.

## Out of scope for this pass

- P0 (replacing picsum.photos placeholders with real screenshots) — explicitly
  deferred by the user earlier in this project, unrelated to this review.
- Anything beyond the homepage sections listed at the top of
  review-homepage.md (admin panel UI, CV PDF layout) — not reviewed here.
