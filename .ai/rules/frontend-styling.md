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
