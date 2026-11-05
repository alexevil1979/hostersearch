<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — HosterSearch</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800" x-data="{ sidebar: true }">
<div class="flex min-h-screen">
    <aside class="hidden w-64 shrink-0 flex-col bg-brand-950 text-slate-200 lg:flex">
        <div class="border-b border-white/10 px-5 py-5">
            <a href="{{ route('admin.dashboard') }}" class="font-semibold text-white">HosterSearch Admin</a>
        </div>
        <nav class="flex-1 space-y-1 px-3 py-4 text-sm">
            @php
                $links = [
                    ['admin.dashboard', 'Dashboard', null],
                    ['admin.companies.index', 'Companies', null],
                    ['admin.reviews.index', 'Reviews', null],
                    ['admin.coupons.index', 'Coupons', null],
                    ['admin.categories.index', 'Categories', null],
                    ['admin.pages.index', 'Pages', null],
                    ['admin.users.index', 'Users', null],
                    ['admin.claims.index', 'Claims', null],
                    ['admin.settings.edit', 'Settings', null],
                    ['admin.activity.index', 'Activity log', null],
                ];
            @endphp
            @foreach ($links as [$route, $label])
                <a href="{{ route($route) }}"
                   class="block rounded-lg px-3 py-2 {{ request()->routeIs(str_replace('.edit', '.*', str_replace('.index', '.*', $route))) || request()->routeIs($route) ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                    {{ $label }}
                </a>
            @endforeach
            <a href="{{ route('home') }}" class="mt-4 block rounded-lg px-3 py-2 text-slate-400 hover:bg-white/5 hover:text-white">← Public site</a>
        </nav>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
            <div class="font-semibold text-brand-900">@yield('heading', 'Admin')</div>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-slate-500">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">Logout</button></form>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <ul class="list-disc pl-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
