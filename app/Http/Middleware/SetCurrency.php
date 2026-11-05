<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('hostersearch.supported_currencies', ['USD', 'EUR']);
        $currency = $request->session()->get('currency')
            ?? $request->user()?->currency
            ?? config('hostersearch.default_currency', 'USD');

        if (! in_array($currency, $supported, true)) {
            $currency = 'USD';
        }

        $request->attributes->set('currency', $currency);
        view()->share('currentCurrency', $currency);

        return $next($request);
    }
}
