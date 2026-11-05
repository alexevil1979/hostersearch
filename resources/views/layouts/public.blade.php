<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Compare web hosting providers, expert reviews, coupons and advisor recommendations.')">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title')) ?: config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Compare web hosting providers and find the best plan.')">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @stack('head')
</head>
<body class="min-h-screen font-sans" x-data="{ mobileOpen: false }">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -left-24 top-0 h-80 w-80 rounded-full bg-brand-200/40 blur-3xl"></div>
        <div class="absolute right-0 top-40 h-96 w-96 rounded-full bg-sky-200/30 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(37,99,235,0.08),_transparent_55%)]"></div>
    </div>

    @include('partials.public-nav')

    @if (session('status'))
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.public-footer')
</body>
</html>
