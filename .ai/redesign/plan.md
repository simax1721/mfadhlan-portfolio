# Redesign Execution Plan

Tracks the work coming out of [review-homepage.md](review-homepage.md).
Update the Status column as items land — don't delete finished rows, so this
stays a record of what changed and why.

**Status: not finished.** Every item logged below is done and verified, but
this redesign pass is still open-ended — it has been proceeding one
user-directed round at a time (Hero feel → icons → loading screen →
animation polish → mobile nav/spacing → section backgrounds → background
simplification/bug fixes → featured project restructure → loader min-delay),
each started by a new instruction from Fadhlan (the user), not from a
fixed backlog. Expect more rounds like this; don't treat the absence of
new open items below as "redesign complete" — check with Fadhlan before
assuming so.

**Process note:** commit/push and doc-update-before-commit rules live in
[.ai/rules/git-workflow.md](../rules/git-workflow.md) — push is not a
standing "never", it happens on explicit request (it already has, mid-redesign:
2026-08-24/25 pushed everything accumulated to that point, after a Railway
Volume was attached for persistent uploads).

## Hero "feels flat" pass (2026-08-24)

User, thinking as a recruiter: does the Hero feel too rigid/flat? Reviewed
via `ui-ux-pro-max` (domain `ux`/`landing`/`style`/`gsap`). Diagnosis: pure
single-column center-aligned text, near-invisible background depth (12-18%
opacity), no visual anchor, plain-text GitHub/LinkedIn links. Presented 3
options (add a visual element / boost depth only / icons then reassess) —
chose adding a visual element.

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| H1 | Terminal/code mockup added as an asymmetric second column on `lg:`+ (illustrative `php artisan serve` + `curl .../api/bootstrap` output, `aria-hidden` since decorative), Hero text switches to left-aligned on `lg:` | `Hero.tsx` | Done — verified 1280px render, hidden below `lg` as intended |
| H1b | Terminal content types out character-by-character (typewriter), line by line, ~22ms/char + 320ms pause between lines; skips straight to final state under `prefers-reduced-motion`; each line's row height is always reserved so nothing shifts vertically as it types | `Hero.tsx` (`useTypewriter` hook) | Done — verified full progression in browser (partial → complete), final text matches exactly, no layout jump, no new horizontal overflow at 1280px. Reduced-motion path implemented per the same `matchMedia` API used elsewhere in the codebase but not separately emulated/tested this pass |
| H5 | Extended the icon treatment to Contact (4 CTA buttons: Email/WhatsApp/GitHub/LinkedIn, was bare text) and Footer (GitHub/LinkedIn, was bare text — Footer's GitHub link also switched from showing the raw URL to a short "GitHub" label, matching Contact) for consistency with Hero. Extracted `GitHubIcon`/`LinkedInIcon` out of `Hero.tsx` plus new `MailIcon`/`WhatsAppIcon` into a shared `icons.tsx` | `components/icons.tsx` (new), `Hero.tsx`, `Contact.tsx`, `Footer.tsx` | Done — verified all 6 links (4 Contact + 2 Footer) render icon+label correctly, no overflow at 375/1280px |
| H6 | Full-page loader (shown while `/api/bootstrap` is in flight) replaced the plain spinner + "Loading portfolio…" text with a terminal window matching Hero's — types `$ curl -s /api/bootstrap`, then shows the localized loading text with a blinking cursor until data arrives. Extracted `TerminalWindow` (window chrome) and `useTypewriter`/`sliceSegments` (was Hero-local) into shared `components/TerminalWindow.tsx` / `hooks/useTypewriter.ts`; Hero refactored to use both instead of its own copies | `components/TerminalWindow.tsx` (new), `hooks/useTypewriter.ts` (new), `StatusScreens.tsx`, `Hero.tsx` (refactor only, no behavior change) | Done — caught the loader mid-animation twice (typing → "Loading portfolio..." with cursor) at both 375px and 1280px; reconfirmed Hero's terminal still works identically post-refactor |

## "Still feels flat past the Hero?" pass (2026-08-24)

User, again thinking as a recruiter, after H1-H6: is the rest of the page's
motion still lacking? Findings (own review, not a fresh skill search): (1)
`useReveal`'s IntersectionObserver has no per-item stagger, so a whole grid
(Skills categories, Project cards, Experience items) fades in as one flat
batch instead of a cascade; (2) `BackToTop` used `if (!visible) return null`
— popped in/out with zero transition; (3) the mobile nav menu used
`{open && (...)}` — same abrupt mount/unmount, no slide/fade. Explicitly
decided *not* to add anything beyond these three (no parallax, no heavier
scroll-storytelling) — over-animating a resume-style site risks feeling
gimmicky and slows down a recruiter's fast scan.

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| A1 | Added `revealDelay(index)` helper (inline `transitionDelay`, 70ms/item) and applied it to every `.reveal` grid/list: Skills categories, Project cards, Experience items, the 2 Education/Organization cards | `lib/reveal.ts` (new), `Skills.tsx`, `Projects.tsx`, `Experience.tsx`, `EducationOrg.tsx` | Done — verified via computed `transitionDelay` in-browser: Skills `0/70/140/210/280ms`, Projects `0/70/140ms`, Experience `0/70/140/210ms` |
| A2 | `BackToTop` always renders now; visibility toggles via `opacity`/`translate-y` + `transition-all duration-300`, plus `aria-hidden`/`tabIndex={-1}` when hidden so it's not keyboard-reachable while invisible | `BackToTop.tsx` | Done — verified opacity 0→1 and `aria-hidden` toggle correctly on scroll |
| A3 | Mobile nav menu always renders now; open/close animates via `max-height`/`opacity`/`padding` + `transition-all duration-300`, `inert` attribute when closed (removes it from focus/tab order and the accessibility tree without manually managing every link's `tabIndex`) | `Navbar.tsx` | Done — verified `max-height`/`opacity`/`inert` toggle correctly on click, screenshot confirms open state renders correctly |
| A4 | *(found while verifying A2)* `.bg-surface`'s hand-written `transition` property list (an existing, documented Tailwind-cascade-layer gotcha — see `.ai/rules/frontend-styling.md`) didn't include `opacity`, so `BackToTop`'s `transition-all` was silently losing the opacity fade to this earlier, unlayered rule — `transform` animated but `opacity` snapped instantly | `index.css` (`.bg-surface`) | Done — added `opacity` to the transition list; reverified `BackToTop`'s computed `transitionProperty` includes it |

## Mobile nav-scroll & spacing fixes (2026-08-24, user-reported)

User: on mobile, clicking a nav-menu link left the target section's heading
hidden under the sticky navbar, and vertical spacing between sections felt
too large. Verification for this pass used `npm run lint` + in-browser
checks only — **no `npm run build`**, per explicit user request (slow on
their machine).

| # | Bug/Change | File(s) | Status |
|---|------------|---------|--------|
| M1 | No `scroll-margin-top` on sections, so anchor-jumping (any viewport) landed the heading flush against/under the sticky navbar | `index.css` (`section[id] { scroll-margin-top: 88px; }`) | Done |
| M2 | Mobile nav link clicks raced the menu's own 300ms close transition against the browser's native anchor-scroll — the scroll target was computed while the menu was still visually open (taller layout), so once the menu collapsed the page had already scrolled past the section heading. Fixed by preventing the default anchor jump, closing the menu, waiting for its transition to finish (320ms), *then* `scrollIntoView` | `Navbar.tsx` (`goToSection`) | Done — verified heading lands ~180px from viewport top (well clear of the 74px navbar) after a full open→click→settle cycle, screenshot confirms |
| M3 | All 6 sections used a flat `py-24` (96px) regardless of viewport — felt excessive on mobile | `About.tsx`, `Contact.tsx`, `EducationOrg.tsx`, `Experience.tsx`, `Projects.tsx`, `Skills.tsx` (`py-24` → `py-16 sm:py-20 md:py-24`) | Done — verified computed padding: 64px at 375px width, unchanged 96px at 1280px (desktop was already correct, untouched) |

## Background: Hero/About/Projects felt like one flat slab (2026-08-24)

User noticed 3 consecutive sections (Hero → About → Projects, per the P3
reorder) all sit on the same flat `--color-bg` with zero texture. Asked
about livening it up — floating shapes, or a vanta.js-style animated
background. Checked the skill's data first: no direct WebGL/vanta guidance,
but `react-performance.csv`'s bundle-size guidance and the "Aurora UI" style
entry (`cost:low|drivers:none` for CSS-only flowing gradients vs. what
would certainly be `cost:high` for a Three.js-backed canvas) both pointed
the same direction. Recommended against vanta.js (adds a Three.js
dependency — hundreds of KB against this project's ~227KB total JS bundle,
continuous WebGL render loop, real cost on the user's own low-spec laptop,
harder to cleanly respect reduced-motion) and proposed a CSS-only
alternative instead; user agreed.

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| B1 | Extracted a reusable `SectionBackground` (dot-grid texture, reusing Hero's existing `.dot-grid` class, + 2 slow-drifting blurred blobs via new `@keyframes float-blob-a/b` + `.bg-blob`) and applied it to About and Projects (Hero keeps its own existing bespoke background, untouched) | `components/SectionBackground.tsx` (new), `index.css` (`.bg-blob`, keyframes), `About.tsx`, `Projects.tsx` | Done |
| B2 | Both sections restructured to a full-width `relative overflow-hidden` `<section>` wrapping an inner `mx-auto max-w-*` content div — needed so the background layer isn't clipped to the narrow content column | `About.tsx`, `Projects.tsx` | Done — verified `position: relative` + `overflow: hidden` computed correctly, 2 blobs present with `float-blob-a`/`float-blob-b` animations attached in both sections |
| B3 | No new work needed for reduced-motion — the blobs are ordinary CSS `animation`s, already covered by the site's existing global `prefers-reduced-motion` kill-switch in `index.css` | — | N/A (covered by existing mechanism) |

Verified (lint + browser only, no `npm run build` per the user's standing
request): no horizontal overflow at 375px or 1280px, blobs render in both
dark and light theme without hurting text contrast (opacity kept low,
10-15%).

### Follow-up: the blobs (and Hero's original background) were invisible — real bug, not just "too subtle"

User reported not seeing any background change at all. Investigation found
a genuine stacking-context bug, not a subtlety/taste issue — confirmed by
testing at `opacity: 1` + `blur(20px)` and still seeing nothing.

**Root cause:** `App.tsx`'s root wrapper (`<div className="min-h-screen
bg-bg">`) is an ordinary `position: static` box with a solid background.
None of `<main>`, `<section className="relative ...">` (no explicit
`z-index`, so `position: relative` alone does **not** create a stacking
context), or that wrapper establish an isolated stacking context. Per the
CSS painting-order spec, a `z-index: -10` descendant with no isolating
ancestor gets compared at the nearest real stacking context — here, the
document root — where it paints *before* (behind) ordinary in-flow boxes
like that root wrapper's own background fill. Net effect: **every
`-z-10` decorative background element on the page, including Hero's
original dot-grid + radial-gradient glow from much earlier this session,
had silently never been visible** — confirmed by testing Hero's dot-grid
at `opacity: 1` and finding it equally invisible.

**Fix:** added `isolate` (`isolation: isolate`) to each section that has
`-z-10` background children — this makes the `<section>` itself a real
stacking context, so its negative-z-index children are contained and
compared only within it (behind its own content, in front of whatever is
behind the section), instead of leaking out to the document root.

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| B4 | Added `isolate` to the 3 sections with `-z-10` background layers | `Hero.tsx`, `About.tsx`, `Projects.tsx` | Done — verified live in-browser (toggled `isolation: isolate` via devtools-equivalent JS first to confirm the fix before editing source); both Hero's original glow and the new About/Projects blobs are now genuinely visible |
| B5 | Blob opacity dialed back down (`40%/35%` → `18%/15%`, blur `60px` → `70px`) — the earlier boosted values were calibrated *while the isolation bug was still hiding them*, so once B4 fixed visibility they read as too strong; retuned to match Hero's existing glow intensity | `SectionBackground.tsx` | Done — reverified visually at both About and Projects, no overflow at 375/1280px |

**Takeaway for future `-z-10` decorative layers in this codebase:** always
pair `-z-10` with `isolate` (or an explicit `z-index` ≥ 0) on the
containing positioned ancestor — recorded in `.ai/rules/frontend-styling.md`
so this doesn't get rediscovered the hard way again.
| H2 | GitHub/LinkedIn hero links: bare text → hand-drawn brand SVG icon + existing text label (matches project's "no icon-only buttons" and "official brand marks" rules) | `Hero.tsx` | Done — verified 2 SVGs present in hero links |
| H3 | Navbar brand title `<simax1721 />` → `simax1721` (dropped the literal angle-bracket styling) | `Navbar.tsx` | Done |
| H4 | *(found while testing, unrelated to the Hero work)* Nav overflow at exactly 768px — "Let's talk" CTA appeared at `md:` (768px) at the same time the hamburger disappeared, combined width exceeded viewport | `Navbar.tsx` | Done — CTA now appears at `lg:` (1024px) instead; reverified no overflow at 360/768/1024/1280 |

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
| P1 | All 4 projects have `image_url: null` — every card (incl. the Featured Case Study) shows a random unrelated picsum.photos stock photo | 🔴 Highest-impact | data (`projects.image_url`) | **Done, live in production** — Aceh Cinema & Amanah Aceh use real screenshots of their live sites (cropped 16:9, user captured + ffmpeg crop). HydroSmart IoT & Berkah Bibit use branded SVG placeholders (dark/cyan theme, honest "Preview coming soon" tag) since they have no live demo. Re-uploaded via the production Filament admin on 2026-08-25 (the local-only upload from when this row was first written didn't carry over — Filament uploads are DB/storage content, not something `git push` moves), verified via `/api/bootstrap` returning real `image_url`s for all 4 |
| P2 | Skill category order led with "Engineering Workflow" (AI tooling) ahead of "Backend" — undercut the site's own positioning | 🟠 High | `PortfolioSeeder.php`, new migration `2026_08_24_090000_reorder_skill_categories_backend_first.php` | **Done, live in production** — reordered Backend→Frontend→Database→Tools→Engineering Workflow; the migration shipped in the 2026-08-25 push and ran automatically via `railway.json`'s `migrate --force` on deploy, verified via `/api/bootstrap` returning "Backend" first |
| P3 | Section order puts Projects (strongest proof-of-work) after Hero→About→Skills→Experience, later than the `portfolio-grid` pattern's Hero→Projects→About | 🟡 Discuss | `App.tsx`, `Navbar.tsx` (section + nav order) | Done — discussed 3 options (full reorder / partial / leave as-is), chose partial: Hero→About→**Projects**→Skills→Experience→Education→Contact. Nav links + scroll-spy `SECTION_IDS` reordered to match. Verified locally (lint, build, live render, nav order) |

## Background simplification & visibility bugs (2026-08-24)

User, as a recruiter, called the per-section backgrounds "not smooth" —
investigation traced it to the B1–B5 pass above having grown into 6
different dot-grid/blob configurations across 7 sections (independently
flipped dots vs. blobs, per section). Rather than adding a 7th variant,
simplified back down.

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| S1 | Dropped the per-section `flip`/`dots` props entirely. `SectionBackground` is one fixed look now, applied only to Hero/Projects/Experience (every other section), so no two textured sections are ever adjacent and the seam problem this was solving doesn't arise in the first place | `SectionBackground.tsx`, `About.tsx`, `EducationOrg.tsx` | Done — verified 0 `.dot-grid` in About/Education, 1 in Projects/Experience |
| S2 | Fixed blobs rendering as a hard-edged wall instead of a soft glow on wide viewports — offsets were in `%`, which scales with section width while the blob's fixed px diameter doesn't, so on wide screens the blob's center sat too far inside the visible area (still fully opaque) before `overflow-hidden` clipped it. Switched to fixed px offsets sized to `radius ± blur radius` | `SectionBackground.tsx` | Done — verified at 1440px, no hard edge at either blob |
| S3 | Nudged the top-left blob down (`top-[5%]` → `top-[18%]`) — its curve was intersecting the section heading and reading as a panel cut | `SectionBackground.tsx` | Done |
| S4 | `FullPageLoader`'s dot-grid + glow were configured but never actually visible — same `isolate`-missing bug as B4 above, just in a component B4's audit didn't cover. Added `isolate` to its wrapper | `StatusScreens.tsx` | Done — verified live (caught the loader mid-render) and via computed `isolation: isolate` |

**Takeaway:** per earlier rounds' "over-animating a resume-style site
feels gimmicky" principle, prefer *fewer* background variants applied
consistently over a bespoke per-section recipe — the latter reads as
inconsistent even when no single section looks wrong in isolation.

## Featured project restructure & project list scaling (2026-08-24)

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| F1 | `FeaturedProject`'s image was cropped hard on desktop — its 2-column grid stretched the image cell to match the (much taller) text column's height, and `object-cover` filled that by cropping. Switched `object-cover` → `object-contain` (+ `bg-surface-2` fill) so the full image always shows regardless of source aspect ratio — also means a future 9:16 upload won't crop either, just letterbox | `FeaturedProject.tsx` | Done — verified Aceh Cinema's logo/tagline, previously cut off, now fully visible at 1200px and 375px |
| F2 | Restructured `FeaturedProject` from a 2-column grid (image left, text right on `md:`+) to one stacked column at every breakpoint (image full-width on top, text below) matching the plain `ProjectCard` pattern, sized up (bigger title, more padding) to still read as the featured one — user expected it to match the other cards' layout, not a bespoke split | `FeaturedProject.tsx` | Done — verified 1200px and 375px |
| F3 | Added "show more" progressive disclosure to the project grid (6 shown by default, button reveals the rest) ahead of an anticipated ~10 projects — considered and rejected a carousel: carousels have well-documented low interaction rates, so anything past the first slide effectively goes unseen, a bad trade for a portfolio. Reuses the same pattern `Experience` already uses for its bullets | `Projects.tsx`, `i18n/locales/{en,id}.ts` (`projects.showMore/showLess`) | Done — verified by temporarily lowering the threshold to 2, clicking through expand/collapse, then restoring it to 6; no button shows with the current 3 non-featured projects |

## Loading-screen min-delay (2026-08-25)

User noticed on the live site (fast, cache-hit `/api/bootstrap`) that the
loader's terminal typewriter got cut off mid-line before finishing —
doesn't happen locally against an uncached `php artisan serve`, where the
request is naturally slower than the animation.

| # | Change | File(s) | Status |
|---|--------|---------|--------|
| L1 | Added `withMinDelay()`, wrapping the bootstrap fetch so the loader shows for at least 1200ms regardless of how fast the request resolves — applied only when `import.meta.env.PROD` is true (Vite's build-time flag), so `npm run dev` is completely unaffected | `lib/minDelay.ts` (new), `App.tsx` | Done — lint + `tsc -b` clean, `withMinDelay` timing verified in isolation (a 50ms promise took ~1200ms total; a 1500ms promise was *not* further delayed, stayed ~1500ms), dev server rechecked to confirm no behavior change there |

## Suggested order

1. ~~Items 1–2 (Critical, accessibility) — small, isolated, low-risk changes.~~ Done.
2. ~~Item 3 (High, font loading) — see [decisions.md](decisions.md).~~ Done.
3. ~~Items 4–5 (Medium) — quick token/spacing tweaks.~~ Done.
4. ~~Item 6 (Minor) — icon stroke-width consistency.~~ Done.
5. ~~Item 7 — was blocked on P0/P1 (real project screenshots).~~ Done — see P1 above, live in production as of 2026-08-25.

## Verification per item

Same pattern used earlier this session: `npm run lint`, `npm run build`,
then a browser check (relevant breakpoint / theme / keyboard nav for the
specific fix) before marking a row Done.

## Out of scope for this pass

- P0 (replacing picsum.photos placeholders with real screenshots) — explicitly
  deferred by the user earlier in this project, unrelated to this review.
- Anything beyond the homepage sections listed at the top of
  review-homepage.md (admin panel UI, CV PDF layout) — not reviewed here.
