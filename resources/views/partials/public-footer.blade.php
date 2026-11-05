<footer class="mt-20 border-t border-slate-200 bg-brand-950 text-slate-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">
        <div class="md:col-span-1">
            <div class="font-display text-2xl font-semibold text-white">HosterSearch</div>
            <p class="mt-3 text-sm leading-relaxed text-slate-400">
                {{ __('Compare providers, read expert reviews, and unlock exclusive coupons.') }}
            </p>
        </div>
        <div>
            <div class="text-sm font-semibold uppercase tracking-wide text-white">Explore</div>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="hover:text-white" href="{{ route('categories.show', 'web-hosting') }}">Web Hosting</a></li>
                <li><a class="hover:text-white" href="{{ route('categories.show', 'wordpress-hosting') }}">WordPress</a></li>
                <li><a class="hover:text-white" href="{{ route('categories.show', 'cloud-hosting') }}">Cloud</a></li>
                <li><a class="hover:text-white" href="{{ route('advisor.index') }}">Advisor</a></li>
            </ul>
        </div>
        <div>
            <div class="text-sm font-semibold uppercase tracking-wide text-white">Company</div>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="hover:text-white" href="{{ route('about') }}">{{ __('About') }}</a></li>
                <li><a class="hover:text-white" href="{{ route('methodology') }}">{{ __('Methodology') }}</a></li>
                <li><a class="hover:text-white" href="{{ route('faq') }}">{{ __('FAQ') }}</a></li>
                <li><a class="hover:text-white" href="{{ route('claim.create') }}">{{ __('Claim listing') }}</a></li>
                <li><a class="hover:text-white" href="{{ route('who-is-hosting') }}">Who is hosting</a></li>
            </ul>
        </div>
        <div>
            <div class="text-sm font-semibold uppercase tracking-wide text-white">Prefs</div>
            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold">
                <a href="{{ route('locale.switch', 'en') }}" class="rounded-lg bg-white/10 px-3 py-1.5 hover:bg-white/20">EN</a>
                <a href="{{ route('locale.switch', 'ru') }}" class="rounded-lg bg-white/10 px-3 py-1.5 hover:bg-white/20">RU</a>
                <a href="{{ route('currency.switch', 'USD') }}" class="rounded-lg bg-white/10 px-3 py-1.5 hover:bg-white/20">USD</a>
                <a href="{{ route('currency.switch', 'EUR') }}" class="rounded-lg bg-white/10 px-3 py-1.5 hover:bg-white/20">EUR</a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 py-4 text-center text-xs text-slate-500">
        © {{ date('Y') }} HosterSearch
    </div>
</footer>
