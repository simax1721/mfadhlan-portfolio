# Bilingual content pattern

Every translatable field is a column pair, not a translation table:
`{field}_id` + `{field}_en` (e.g. `summary_id`/`summary_en`).

- **Model:** use `HasLocalizedFields` (`app/Models/Concerns/`) and call
  `->trans('field')` in resources/views — it resolves the active locale
  with an English fallback. Don't read `_en`/`_id` columns directly outside
  the trait.
- **Locale source:** `SetApiLocale` middleware reads `?lang=` on every API
  route (defaults to `en`) and sets the app locale for the request. New API
  routes get this automatically since it's applied globally — don't add
  per-route locale handling.
- **Frontend:** `LocaleContext` (`frontend/src/i18n/`) drives the same
  `?lang=` param on every `api.*` call and refetches on change. UI chrome
  strings (buttons, labels — not content) live in
  `frontend/src/i18n/locales/{en,id}.ts`, typed against each other so a
  missing key in one locale fails the build.
- **New translatable field checklist:** migration adds both `_id`/`_en`
  columns → add to model `$fillable` (and `$casts` if array/JSON, see
  `Profile::highlights_en/id`) → resource exposes it via `->trans('field')`
  → Filament form gets both a `_en` and `_id` input, usually inside the
  existing EN/ID `Tabs` component (see `ManageProfile.php`) → seeder sets
  both.
