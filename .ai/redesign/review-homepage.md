# Homepage UI/UX Review

**Date:** 2026-08-24
**Scope:** `frontend/src/components/` — Navbar, Hero, About, Skills, Experience,
FeaturedProject, Projects, EducationOrg, Contact, Footer, BackToTop,
LanguageToggle, ThemeToggle.
**Method:** Manual read of every homepage component, cross-checked against the
`ui-ux-pro-max` skill's Quick Reference checklist, plus two targeted dataset
queries (`touch target icon button size`, `skip to main content link`,
domain `ux`). Platform: **desktop web** (not native/mobile app) — touch-target
findings use the WCAG 2.2 AA web standard (24×24 CSS px), not the 44×44pt
native-app standard the skill's checklist defaults to. See
[decisions.md](decisions.md) for why.

Status legend: 🔴 CRITICAL · 🟠 HIGH · 🟡 MEDIUM · 🟢 minor/polish

---

## 🔴 CRITICAL

### 1. Mobile nav hamburger button — hit-area right at the minimum
- **File:** `frontend/src/components/Navbar.tsx:122-137`
- **Issue:** `<button>` has no padding classes at all, just a 24×24 SVG child.
  Tailwind's preflight resets button padding to 0, so the actual tap target
  is the SVG's bare 24×24 bounding box — exactly at WCAG 2.2 AA's 24 CSS px
  minimum, with zero margin for rendering variance. This is the primary
  mobile nav trigger (opens the entire menu).
- **Fix:** add `p-2` to the button (visual icon stays 24×24, hit area grows
  to ~40×40).

### 2. No skip-link
- **Issue:** The page is nav-heavy (sticky navbar + 6 sections) but has no
  "Skip to main content" link. Keyboard/screen-reader users must tab through
  the full nav every time.
- **Fix:** add a visually-hidden-until-focused skip link
  (`sr-only focus:not-sr-only`) as the first focusable element in
  `frontend/src/App.tsx`, before `<Navbar>`, pointing at `#top` or a new
  `<main id="main-content">` landmark.

---

## 🟠 HIGH

### 3. Inter / JetBrains Mono declared but never loaded
- **File:** `frontend/src/index.css:4-5`, `frontend/index.html`
- **Issue:** `--font-sans: "Inter", ...` and `--font-mono: "JetBrains Mono", ...`
  are declared as CSS theme tokens, but no `<link>` to Google Fonts (or any
  `@font-face`) exists anywhere in the project. The browser silently falls
  back to the OS system font stack. `font-mono` in particular carries a lot
  of the site's "developer/technical" visual identity (eyebrow labels,
  badges, the `<name />` navbar brand) — currently rendering inconsistently
  per OS (Segoe UI on Windows, SF Mono on Mac, etc.) instead of the intended
  consistent typeface.
- **Fix:** add a Google Fonts `<link>` (with `font-display: swap`) for Inter
  + JetBrains Mono in `frontend/index.html`, or if font-loading cost isn't
  worth it, change the CSS tokens to name only fonts that are actually
  available so the declared and rendered fonts match.

---

## 🟡 MEDIUM

### 4. `text-dim` contrast borderline in light mode
- **File:** `frontend/src/components/Footer.tsx:9`
- **Issue:** Footer copyright/link text sits directly on `--color-bg`
  (not inside a `bg-surface` card). Computed contrast of `#64748b` on
  `#f8fafc` (light mode) is ≈4.5:1 — right at the WCAG AA line for normal
  text, with no margin.
- **Fix:** darken `--color-text-dim` slightly in light mode (e.g. `#5b6b82`),
  or verify with a contrast-checker tool and confirm it's comfortably ≥4.5:1.

### 5. `LanguageToggle` buttons slightly under comfortable tap size
- **File:** `frontend/src/components/LanguageToggle.tsx:17`
- **Issue:** `px-2.5 py-1` + `text-xs` → ~24px tall, right at the minimum,
  with no breathing room. Sits next to `ThemeToggle` (32×32px), so the two
  controls look visually uneven in height.
- **Fix:** bump padding to `px-3 py-1.5`.

---

## 🟢 Minor / polish

- **Generic alt text** — `alt={project.title}` in
  `frontend/src/components/Projects.tsx:16` and
  `frontend/src/components/FeaturedProject.tsx:14`. Fine for now (temporary
  picsum.photos placeholders, tracked separately as P0), but should get more
  descriptive once real screenshots land.
- **Icon stroke-width inconsistency** — `ThemeToggle`/`Navbar` use
  `strokeWidth="1.75"`, `BackToTop` uses `"2"`. Barely visible, easy to
  align if doing an icon pass anyway.

---

## ✅ Already good (no action needed)

- Heading hierarchy is clean: one `h1`, sibling `h2`s per section, no skipped
  levels.
- `prefers-reduced-motion` is handled globally
  (`frontend/src/index.css:183-192`); scroll-spy nav links already carry
  `aria-current`.
- Images already use `loading="lazy"` + `aspect-video` (reserves space,
  prevents CLS).
- Primary text/heading contrast against `bg`/`surface` is comfortably
  above 4.5:1 in both themes.
- No emoji used as structural icons — all SVG.
- No horizontal scroll at 360/768/desktop (verified earlier this session).
