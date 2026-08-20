<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * Clears the /api/bootstrap and /api/cv caches (both locales) whenever a
 * portfolio model changes, so edits made in Filament show up on the public
 * site — and in the next CV download — right away.
 */
trait InvalidatesPortfolioCache
{
    protected static function bootInvalidatesPortfolioCache(): void
    {
        static::saved(fn () => self::forgetPortfolioBootstrapCache());
        static::deleted(fn () => self::forgetPortfolioBootstrapCache());
    }

    protected static function forgetPortfolioBootstrapCache(): void
    {
        Cache::forget('portfolio.bootstrap.en');
        Cache::forget('portfolio.bootstrap.id');
        Cache::forget('portfolio.cv.en');
        Cache::forget('portfolio.cv.id');
    }
}
