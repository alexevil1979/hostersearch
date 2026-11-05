<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function locale(Request $request, string $locale): RedirectResponse
    {
        $supported = config('hostersearch.supported_locales', ['en', 'ru']);
        abort_unless(in_array($locale, $supported, true), 404);

        $request->session()->put('locale', $locale);

        if ($request->user()) {
            $request->user()->update(['locale' => $locale]);
        }

        return back();
    }

    public function currency(Request $request, string $currency): RedirectResponse
    {
        $currency = strtoupper($currency);
        $supported = config('hostersearch.supported_currencies', ['USD', 'EUR']);
        abort_unless(in_array($currency, $supported, true), 404);

        $request->session()->put('currency', $currency);

        if ($request->user()) {
            $request->user()->update(['currency' => $currency]);
        }

        return back();
    }
}
