# Cache invalidation

`GET /api/bootstrap` and `GET /api/cv` are cached per locale
(`portfolio.bootstrap.{en,id}`, `portfolio.cv.{en,id}`) because per-request
PHP bootstrap cost, not dompdf/query time, dominates response time in this
environment (Windows/Laragon dev, and the current Railway plan).

- Every model whose data appears in the bootstrap payload or the CV must use
  the `InvalidatesPortfolioCache` trait (`app/Models/Concerns/`). It hooks
  `booted()` to forget the relevant cache keys on save/delete.
- If you add a new model that should show up on the public site, add this
  trait — otherwise Filament edits won't appear on the live site until the
  cache naturally expires or someone runs `cache:clear`.
- Nested `JsonResource`s must call `->resolve()` before being cached (e.g.
  `SkillCategoryResource`'s `skills` relation) — an unresolved nested
  resource serializes to `__PHP_Incomplete_Class_Name` garbage once pulled
  from `Cache::remember()`. This bit us once; don't reintroduce it.
