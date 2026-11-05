@extends('layouts.public')

@section('title', 'HosterSearch — '.__('Find the best web hosting'))
@section('meta_description', __('Compare providers, read expert reviews, and unlock exclusive coupons.'))

@section('content')
<section class="relative overflow-hidden">
    <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
        <div class="animate-[fadeIn_0.6s_ease-out]">
            <p class="mb-4 inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand-800">HosterSearch 2026</p>
            <h1 class="font-display text-4xl font-semibold leading-tight text-brand-950 sm:text-5xl lg:text-6xl">
                {{ __('Find the best web hosting') }}
            </h1>
            <p class="mt-5 max-w-xl text-lg text-slate-600">
                {{ __('Compare providers, read expert reviews, and unlock exclusive coupons.') }}
            </p>
            <form action="{{ route('search') }}" method="get" class="mt-8 flex flex-col gap-3 sm:flex-row">
                <input type="search" name="q" required placeholder="{{ __('Search hosting...') }}"
                       class="w-full flex-1 rounded-2xl border-slate-200 bg-white px-5 py-3.5 text-base shadow-soft focus:border-brand-500 focus:ring-brand-500">
                <button class="btn-primary !rounded-2xl !px-8 !py-3.5">Search</button>
            </form>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('advisor.index') }}" class="btn-secondary">{{ __('Web Hosting Advisor') }}</a>
                <a href="{{ route('comparisons.index') }}" class="btn-secondary">{{ __('Compare') }}</a>
            </div>
        </div>
        <div class="relative hidden lg:block">
            <div class="absolute inset-0 rounded-[2rem] bg-gradient-to-br from-brand-700 via-brand-800 to-sky-700 opacity-90"></div>
            <div class="relative rounded-[2rem] border border-white/10 p-8 text-white shadow-soft">
                <div class="text-sm uppercase tracking-[0.2em] text-brand-100">Editor's picks</div>
                <div class="mt-6 space-y-4">
                    @foreach ($recommended->take(3) as $i => $company)
                        <a href="{{ route('companies.show', $company) }}" class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 backdrop-blur transition hover:bg-white/15">
                            <div>
                                <div class="font-semibold">#{{ $i + 1 }} {{ t_field($company, 'name') }}</div>
                                <div class="text-sm text-brand-100">{{ money($company->price_from) }}/mo · {{ number_format($company->overall_score, 1) }}/5</div>
                            </div>
                            <span class="rounded-lg bg-white/20 px-2 py-1 text-xs">View</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-end justify-between gap-4">
        <div>
            <h2 class="section-title">{{ __('Most Recommended') }}</h2>
            <p class="mt-2 text-slate-600">{{ __('Top-rated providers based on our methodology and verified reviews.') }}</p>
        </div>
        <a href="{{ route('categories.show', 'web-hosting') }}" class="hidden text-sm font-semibold text-brand-700 hover:text-brand-900 sm:inline">View all →</a>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($recommended as $company)
            <x-company-card :company="$company" />
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <h2 class="section-title mb-8">{{ __('Browse by category') }}</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($categories as $category)
            <a href="{{ route('categories.show', $category) }}" class="card-surface group p-6 transition hover:-translate-y-0.5 hover:border-brand-300">
                <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-700 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
                <div class="font-semibold text-brand-900">{{ t_field($category, 'name') }}</div>
                <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ t_field($category, 'description') }}</p>
            </a>
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-2">
        <div>
            <h2 class="section-title mb-6">{{ __('Popular comparisons') }}</h2>
            <div class="space-y-3">
                @foreach ($comparisons as $comparison)
                    <a href="{{ route('comparisons.show', $comparison->slug) }}" class="card-surface flex items-center justify-between px-5 py-4 transition hover:border-brand-300">
                        <div>
                            <div class="font-semibold text-brand-900">{{ t_field($comparison, 'title') }}</div>
                            <div class="text-xs text-slate-500">{{ $comparison->companies->pluck('name')->join(' · ') }}</div>
                        </div>
                        <span class="text-brand-700">→</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            <h2 class="section-title mb-6">{{ __('Exclusive coupons') }}</h2>
            <div class="space-y-3">
                @foreach ($coupons as $coupon)
                    <div class="card-surface flex items-center justify-between gap-4 px-5 py-4">
                        <div>
                            <div class="font-semibold text-brand-900">{{ t_field($coupon, 'title') }}</div>
                            <div class="text-sm text-slate-500">{{ $coupon->company?->name }} · <span class="font-mono font-semibold text-brand-700">{{ $coupon->code }}</span></div>
                        </div>
                        <a href="{{ route('go', ['company' => $coupon->company->slug, 'coupon' => $coupon->id, 'src' => 'home_coupon']) }}" rel="nofollow sponsored" class="btn-accent !py-2">{{ __('Get deal') }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-[2rem] bg-gradient-to-r from-brand-900 via-brand-800 to-sky-800 px-8 py-12 text-white shadow-soft sm:px-12">
        <h2 class="font-display text-3xl font-semibold sm:text-4xl">{{ __('Trust our methodology') }}</h2>
        <p class="mt-3 max-w-2xl text-brand-100">{{ __('Independent tests, moderated reviews, transparent scoring.') }}</p>
        <a href="{{ route('methodology') }}" class="mt-6 inline-flex rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-brand-900 transition hover:bg-brand-50">{{ __('Read methodology') }}</a>
    </div>
</section>
@endsection
