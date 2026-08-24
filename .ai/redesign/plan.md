# Redesign Execution Plan

Tracks the work coming out of [review-homepage.md](review-homepage.md).
Update the Status column as items land — don't delete finished rows, so this
stays a record of what changed and why.

**Process note:** per user instruction, no `git push` during this redesign —
commit locally as work lands, push only when explicitly asked.

## Homepage fixes (from review-homepage.md)

| # | Finding | Severity | File(s) | Status |
|---|---------|----------|---------|--------|
| 1 | Mobile nav hamburger hit-area (add `p-2`) | 🔴 Critical | `Navbar.tsx` | Pending |
| 2 | No skip-link | 🔴 Critical | `App.tsx` | Pending |
| 3 | Inter/JetBrains Mono declared but never loaded | 🟠 High | `index.html`, `index.css` | Pending |
| 4 | `text-dim` contrast borderline in light mode | 🟡 Medium | `index.css` (`--color-text-dim` light) | Pending |
| 5 | `LanguageToggle` tap size | 🟡 Medium | `LanguageToggle.tsx` | Pending |
| 6 | Icon stroke-width consistency (1.75 vs 2) | 🟢 Minor | `ThemeToggle.tsx`, `Navbar.tsx`, `BackToTop.tsx` | Pending |
| 7 | More descriptive alt text | 🟢 Minor | `Projects.tsx`, `FeaturedProject.tsx` | Blocked on P0 (real screenshots) |

## Suggested order

1. Items 1–2 (Critical, accessibility) — small, isolated, low-risk changes.
2. Item 3 (High, font loading) — pick an approach first (see
   [decisions.md](decisions.md) open question), then implement.
3. Items 4–5 (Medium) — quick token/spacing tweaks.
4. Item 6 (Minor) — bundle with whichever component touch already includes
   one of the affected icons, rather than a standalone pass.
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
