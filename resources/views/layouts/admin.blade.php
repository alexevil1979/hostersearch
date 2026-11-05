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
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800" x-data="{ open: false }">
<div class="flex min-h-screen">
    <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full flex-col bg-brand-950 text-slate-200 transition lg:static lg:flex lg:translate-x-0"
           :class="open ? 'translate-x-0 flex' : 'hidden lg:flex'">
        <div class="border-b border-white/10 px-5 py-5">
            <a href="{{ route('admin.dashboard') }}" class="font-semibold text-white">HosterSearch Admin</a>
        </div>
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4 text-sm">
            @foreach ([
                ['admin.dashboard', 'Dashboard', 'admin.dashboard'],
                ['admin.companies.index', 'Companies', 'admin.companies.*'],
                ['admin.reviews.index', 'Reviews', 'admin.reviews.*'],
                ['admin.coupons.index', 'Coupons', 'admin.coupons.*'],
                ['admin.categories.index', 'Categories', 'admin.categories.*'],
                ['admin.pages.index', 'Pages', 'admin.pages.*'],
                ['admin.users.index', 'Users', 'admin.users.*'],
                ['admin.claims.index', 'Claims', 'admin.claims.*'],
                ['admin.settings.edit', 'Settings', 'admin.settings.*'],
                ['admin.activity.index', 'Activity log', 'admin.activity.*'],
            ] as [$route, $label, $active])
                <a href="{{ route($route) }}"
                   class="block rounded-lg px-3 py-2 {{ request()->routeIs($active) ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                    {{ $label }}
                </a>
            @endforeach
            <div class="mt-4 border-t border-white/10 pt-3 text-xs uppercase tracking-wide text-slate-500 px-3">Export CSV</div>
            <a href="{{ route('admin.export.companies') }}" class="block rounded-lg px-3 py-2 hover:bg-white/5">Companies</a>
            <a href="{{ route('admin.export.reviews') }}" class="block rounded-lg px-3 py-2 hover:bg-white/5">Reviews</a>
            <a href="{{ route('admin.export.clicks') }}" class="block rounded-lg px-3 py-2 hover:bg-white/5">Clicks</a>
            <a href="{{ route('home') }}" class="mt-4 block rounded-lg px-3 py-2 text-slate-400 hover:bg-white/5 hover:text-white">← Public site</a>
        </nav>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                <button type="button" class="rounded-lg border border-slate-200 p-2 lg:hidden" @click="open = !open" aria-label="Menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="font-semibold text-brand-900">@yield('heading', 'Admin')</div>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span class="hidden text-slate-500 sm:inline">{{ auth()->user()->name }}</span>
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
<div class="fixed inset-0 z-30 bg-black/40 lg:hidden" x-show="open" x-cloak @click="open=false"></div>
</body>
</html>
