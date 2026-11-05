<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('hostersearch.supported_locales', ['en', 'ru']);
        $locale = $request->session()->get('locale')
            ?? $request->user()?->locale
            ?? $request->getPreferredLanguage($supported)
            ?? config('app.locale', 'en');

        if (! in_array($locale, $supported, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
