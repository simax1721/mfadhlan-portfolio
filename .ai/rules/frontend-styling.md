# Frontend styling (Tailwind v4)

- **CSS-first config, no `tailwind.config.js`:** theme tokens live in
  `frontend/src/index.css` under `@theme`. Light mode is a
  `:root[data-theme="light"]` override block placed *outside* Tailwind's
  generated `@layer theme`, so its unlayered cascade wins over the dark
  defaults without `!important`.
- **Exception — `box-shadow` needs `!important`:** Tailwind's own preflight
  initializes a `box-shadow`/ring variable stack on every element. Plain CSS
  targeting a Tailwind-generated class (`.bg-surface`, `.btn-primary`, etc.)
  loses that specificity fight unless `!important` is used — see the
  comments already in `index.css` around those rules before "fixing" them
  away.
- **Touch-target standard is the web one, not the native-app one:** this is
  a desktop web SPA. Use WCAG 2.2 AA's 24×24 CSS px minimum for interactive
  elements, not the 44×44pt (iOS) / 48×48dp (Android) figures that
  design-skill checklists often default to — those are native-app
  standards and don't apply here. See `.ai/redesign/decisions.md` for the
  full reasoning if this comes up again.
- **Two context providers are split into provider + hook files
  on purpose:** `LocaleContext.tsx`/`useLocale.ts` and
  `ThemeContext.tsx`/`useTheme.ts` are each split specifically so the
  provider file doesn't also export a hook — keeps the
  `react(only-export-components)` Fast Refresh lint rule happy. Follow the
  same split for any new context.
- **`-z-10` decorative backgrounds need `isolate` on their positioned
  ancestor, or they render invisible:** `App.tsx`'s root wrapper
  (`<div className="min-h-screen bg-bg">`) is a plain `position: static`
  box with a solid background, and `position: relative` alone (no explicit
  `z-index`) does **not** create a stacking context. Without an isolating
  ancestor, a `z-index: -10` decorative element (dot-grid, glow, blobs — see
  `SectionBackground.tsx`) gets compared at the document root, where it
  paints *behind* that root wrapper's own background fill — completely
  hidden, not just faint. This bit Hero's original background for most of
  a session before anyone noticed (see `.ai/redesign/plan.md`, "the blobs
  were invisible"). Always add `isolate` to the `<section>` (or whichever
  positioned ancestor) that hosts a `-z-10` child.
