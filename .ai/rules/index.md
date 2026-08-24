# Rules Index

Maps file globs to rule files in this directory. Before editing any file
below, read every rule file whose glob covers it, then
`grep -rin '<keyword>' .ai/rules` for anything a glob match alone would miss.

| Glob | Rule file | Covers |
|------|-----------|--------|
| `backend/app/Models/**`, `backend/database/migrations/**`, `backend/app/Http/Resources/**`, `backend/app/Http/Middleware/SetApiLocale.php`, `frontend/src/i18n/**` | [bilingual-content.md](bilingual-content.md) | Adding/editing any translatable field, end to end |
| `backend/app/Models/**` | [cache-invalidation.md](cache-invalidation.md) | New models, or models gaining content shown on the public site |
| `backend/railway.json`, `backend/bootstrap/app.php`, `backend/app/Providers/**`, `backend/database/seeders/**` | [deploy-gotchas.md](deploy-gotchas.md) | Production deploy config, seeders |
| `frontend/src/index.css`, `frontend/src/components/**` | [frontend-styling.md](frontend-styling.md) | Tailwind v4 theming, touch-target sizing |
| `**` (whole repo) | [git-workflow.md](git-workflow.md) | Any commit/push |
