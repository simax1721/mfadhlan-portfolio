<?php

namespace App\Models\Concerns;

/**
 * For models with `{field}_id` / `{field}_en` column pairs. Reads the
 * current app locale's value, falling back to the other locale if empty.
 */
trait HasLocalizedFields
{
    public function trans(string $field): mixed
    {
        $locale = app()->getLocale();
        $other = $locale === 'en' ? 'id' : 'en';

        $value = $this->{"{$field}_{$locale}"} ?? null;

        if (is_array($value) ? count($value) === 0 : blank($value)) {
            $value = $this->{"{$field}_{$other}"} ?? null;
        }

        return $value;
    }
}
