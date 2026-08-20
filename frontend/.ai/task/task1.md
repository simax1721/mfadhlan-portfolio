# Task: Improve the light-theme visual design

## Context

This is a React + TypeScript + Vite portfolio, styled with Tailwind CSS v4.
The current light theme looks too flat: the page background, surfaces, and
borders are too similar, so sections and cards have little visual depth.

The relevant design tokens live in `src/index.css`. Components are in
`src/components/`.

## Goal

Make the light theme feel polished, personal, and modern for a developer
portfolio while retaining the existing dark theme, responsive layout, content,
and overall clean visual style.

Use a restrained **clean technical editorial** direction: structured and
professional, with subtle visual depth rather than heavy glassmorphism or
excessive gradients.

## Required changes

1. Improve the light-theme color system in `src/index.css`.

   Suggested starting palette:

   - Page background: `#F8FAFC` (or a similarly soft off-white)
   - Card surface: `#FFFFFF`
   - Raised/subtle surface: a pale blue or slate tint
   - Main text: `#0F172A`
   - Accent: cyan around `#0891B2`
   - Second accent: blue around `#2563EB`
   - Featured/detail accent may use amber around `#F59E0B`, sparingly

   Ensure the resulting text and interactive states retain good contrast.

2. Create depth in light mode.

   - Give cards a subtle shadow appropriate to the light theme.
   - Keep borders light and understated; do not rely on dark/thick borders.
   - Improve hover states for project cards and primary/secondary buttons.
   - Preserve the existing dark-theme appearance as closely as practical.

3. Add understated decoration, scoped to suitable sections rather than every
   section.

   - Enhance the Hero background using the existing radial gradient concept:
     use one or two soft, low-opacity cyan/blue glows or a very subtle mesh.
   - Add a faint technical dot-grid or line-grid in Hero and/or Projects.
     It must not reduce text readability or create horizontal overflow.
   - Add gentle alternating section backgrounds (for example, a pale blue
     treatment for Experience or Skills) to improve visual rhythm.
   - Optionally use a very subtle noise/texture effect, but only if it works
     without adding an image dependency.

4. Improve empty project media.

   The current project card shows only the project title when `image_url` is
   absent. Design a tasteful CSS-only placeholder/mockup with a small visual
   structure (e.g. browser window, code lines, or abstract layout) while
   retaining the project title/accessibility context. Do not use external
   image assets.

5. Do not change API shapes, translations, routes, or the portfolio content.
   Do not add dependencies unless absolutely necessary.

## Technical follow-up

Review and, where safe, resolve these existing lint warnings:

- `src/theme/ThemeContext.tsx` and `src/i18n/LocaleContext.tsx`:
  `react(only-export-components)` Fast Refresh warning.
- `src/hooks/useFetch.ts`: `react-hooks(exhaustive-deps)` warning caused by
  the effect dependency pattern.

Do not suppress warnings merely to hide them; preserve intended behavior,
especially refetching when locale changes.

## Acceptance criteria

- Light mode clearly has more hierarchy and depth, without becoming visually
  busy.
- Dark mode remains usable and visually coherent.
- The layout remains responsive on mobile and desktop.
- No horizontal scroll is introduced.
- Keyboard focus states remain visible for links and controls.
- `npm.cmd run lint` completes with no warnings if the warning cleanup is
  implemented.
- `npm.cmd run build` completes successfully.

## Implementation notes

- Prefer CSS variables and utility classes compatible with Tailwind v4.
- Use `data-theme="light"` for light-theme-specific CSS.
- Respect `prefers-reduced-motion` for any newly introduced motion.
- Keep decorations behind content (`pointer-events: none`) and low contrast.
