<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocale
{
    private const SUPPORTED = ['en', 'id'];

    private const DEFAULT = 'en';

    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->query('lang', self::DEFAULT);
        $locale = in_array($lang, self::SUPPORTED, true) ? $lang : self::DEFAULT;

        app()->setLocale($locale);

        return $next($request);
    }
}
