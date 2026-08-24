# Frontend — M. Fadhlan Portfolio

React 19 + Vite + TypeScript + Tailwind CSS 4. A single-page public
portfolio site that reads all its content live from the Laravel API —
no hardcoded copy.

See the [repo root README](../README.md) for the full project overview,
local setup for both apps, and deployment notes.

## Structure

```
src/components/    One component per homepage section (Hero, About, Skills,
                    Experience, Projects, FeaturedProject, EducationOrg,
                    Contact, Navbar, Footer, BackToTop, ...)
src/i18n/           LocaleContext (EN/ID) + per-locale copy in locales/
src/theme/          ThemeContext (light/dark), persisted client-side
src/hooks/          useFetch (data loading), useReveal (scroll-in animation)
src/lib/            api client, shared types, projectImage helper
```

Context providers (`LocaleContext`, `ThemeContext`) are each split into a
provider file plus a separate hook file (`useLocale.ts` / `useTheme.ts`)
so Fast Refresh's `react(only-export-components)` lint rule stays happy.

## Content & data

The whole site is driven by one request — `GET /api/bootstrap?lang=en|id`
— fetched once in `App.tsx` via `useFetch`. Changing the language toggle
refetches with the new `lang` param; there's no separate translation
layer on the frontend, all text comes from the API already resolved to
the active locale (UI chrome strings like "Show more" live in
`src/i18n/locales/`).

The one project flagged `featured` in the admin renders as an expanded
case-study card (`FeaturedProject.tsx`) instead of a standard project
card.

## Local setup

```bash
npm install
npm run dev
```

Requires the backend running (`http://localhost:8000` by default — see
[backend/README.md](../backend/README.md)). Set `VITE_API_URL` in
`.env` if the backend runs elsewhere; defaults to
`http://localhost:8000/api`.

## Scripts

```bash
npm run dev       # dev server with HMR
npm run build     # tsc type-check + vite production build
npm run lint       # oxlint
npm run preview   # preview the production build locally
```

## Tech stack

React 19 · Vite · TypeScript · Tailwind CSS 4 (CSS-first `@theme` config) · Oxlint
