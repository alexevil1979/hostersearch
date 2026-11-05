<header class="sticky top-0 z-40 border-b border-white/40 bg-white/80 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="group flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-800 text-sm font-bold text-white shadow-soft transition group-hover:scale-105">HS</span>
            <span class="font-display text-xl font-semibold text-brand-900">HosterSearch</span>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 lg:flex">
            <a href="{{ route('categories.show', 'web-hosting') }}" class="hover:text-brand-800">Hosting</a>
            <a href="{{ route('categories.show', 'vps') }}" class="hover:text-brand-800">VPS</a>
            <a href="{{ route('comparisons.index') }}" class="hover:text-brand-800">{{ __('Compare') }}</a>
            <a href="{{ route('advisor.index') }}" class="hover:text-brand-800">Advisor</a>
            <a href="{{ route('coupons.index') }}" class="hover:text-brand-800">{{ __('Coupons') }}</a>
        </nav>

        <div class="hidden items-center gap-3 md:flex">
            <form action="{{ route('search') }}" method="get" class="relative">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search hosting...') }}"
                       class="w-44 rounded-xl border-slate-200 bg-slate-50 py-2 pl-3 pr-3 text-sm focus:border-brand-500 focus:ring-brand-500 lg:w-56">
            </form>

            <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 text-xs font-semibold">
                <a href="{{ route('locale.switch', 'en') }}" class="rounded-lg px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-brand-800 text-white' : 'text-slate-500' }}">EN</a>
                <a href="{{ route('locale.switch', 'ru') }}" class="rounded-lg px-2 py-1 {{ app()->getLocale() === 'ru' ? 'bg-brand-800 text-white' : 'text-slate-500' }}">RU</a>
            </div>
            <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 text-xs font-semibold">
                <a href="{{ route('currency.switch', 'USD') }}" class="rounded-lg px-2 py-1 {{ ($currentCurrency ?? 'USD') === 'USD' ? 'bg-brand-800 text-white' : 'text-slate-500' }}">USD</a>
                <a href="{{ route('currency.switch', 'EUR') }}" class="rounded-lg px-2 py-1 {{ ($currentCurrency ?? 'USD') === 'EUR' ? 'bg-brand-800 text-white' : 'text-slate-500' }}">EUR</a>
            </div>

            @auth
                <a href="{{ route('dashboard') }}" class="btn-secondary !py-2">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-800">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="btn-primary !py-2">{{ __('Register') }}</a>
            @endauth
        </div>

        <button type="button" class="rounded-xl border border-slate-200 p-2 lg:hidden" @click="mobileOpen = !mobileOpen" aria-label="Menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div class="border-t border-slate-100 bg-white px-4 py-4 lg:hidden" x-show="mobileOpen" x-cloak>
        <form action="{{ route('search') }}" method="get" class="mb-4">
            <input type="search" name="q" placeholder="{{ __('Search hosting...') }}" class="w-full rounded-xl border-slate-200 text-sm">
        </form>
        <div class="flex flex-col gap-3 text-sm font-medium">
            <a href="{{ route('categories.show', 'web-hosting') }}">Hosting</a>
            <a href="{{ route('categories.show', 'vps') }}">VPS</a>
            <a href="{{ route('comparisons.index') }}">{{ __('Compare') }}</a>
            <a href="{{ route('advisor.index') }}">Advisor</a>
            <a href="{{ route('coupons.index') }}">{{ __('Coupons') }}</a>
            @auth
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('login') }}">{{ __('Login') }}</a>
                <a href="{{ route('register') }}">{{ __('Register') }}</a>
            @endauth
        </div>
    </div>
</header>
