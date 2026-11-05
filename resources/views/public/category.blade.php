@extends('layouts.public')

@section('title', t_field($category, 'name').' — HosterSearch')
@section('meta_description', t_field($category, 'description'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-brand-600">Best of</p>
        <h1 class="section-title mt-1">{{ t_field($category, 'name') }}</h1>
        <p class="mt-3 max-w-3xl text-slate-600">{{ t_field($category, 'description') }}</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
        <aside class="card-surface h-fit p-5">
            <h2 class="mb-4 font-semibold text-brand-900">{{ __('Filters') }}</h2>
            <form method="get" class="space-y-4 text-sm">
                <div>
                    <label class="mb-1 block font-medium">Min score</label>
                    <input type="number" step="0.1" min="1" max="5" name="min_score" value="{{ request('min_score') }}" class="w-full rounded-xl border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="mb-1 block font-medium">Min $</label>
                        <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" class="w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Max $</label>
                        <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" class="w-full rounded-xl border-slate-200">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block font-medium">Type</label>
                    <select name="type" class="w-full rounded-xl border-slate-200">
                        <option value="">Any</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->slug }}" @selected(request('type') === $type->slug)>{{ t_field($type, 'name') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block font-medium">OS</label>
                    <select name="os" class="w-full rounded-xl border-slate-200">
                        <option value="">Any</option>
                        <option value="Linux" @selected(request('os') === 'Linux')>Linux</option>
                        <option value="Windows" @selected(request('os') === 'Windows')>Windows</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block font-medium">Panel</label>
                    <input type="text" name="panel" value="{{ request('panel') }}" placeholder="cPanel, hPanel..." class="w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="mb-1 block font-medium">Location</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="US, DE..." class="w-full rounded-xl border-slate-200">
                </div>
                <div class="flex gap-2">
                    <button class="btn-primary flex-1 !py-2">{{ __('Apply') }}</button>
                    <a href="{{ route('categories.show', $category) }}" class="btn-secondary !py-2">{{ __('Reset') }}</a>
                </div>
            </form>
        </aside>

        <div>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-slate-500">{{ $companies->total() }} providers</p>
                <form method="get" class="flex items-center gap-2 text-sm">
                    @foreach (request()->except('sort', 'page') as $key => $value)
                        @if (is_scalar($value))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label class="font-medium">{{ __('Sort by') }}</label>
                    <select name="sort" onchange="this.form.submit()" class="rounded-xl border-slate-200">
                        <option value="score" @selected($sort === 'score')>{{ __('Score') }}</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>{{ __('Price: low to high') }}</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>{{ __('Price: high to low') }}</option>
                        <option value="reviews" @selected($sort === 'reviews')>{{ __('Most reviews') }}</option>
                        <option value="name" @selected($sort === 'name')>{{ __('Name') }}</option>
                    </select>
                </form>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                @forelse ($companies as $company)
                    <x-company-card :company="$company" />
                @empty
                    <div class="card-surface col-span-full p-8 text-center text-slate-500">{{ __('No results found.') }}</div>
                @endforelse
            </div>

            <div class="mt-8">{{ $companies->links() }}</div>
        </div>
    </div>
</div>
@endsection
