@extends('layouts.public')

@section('title', 'Search — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">Search</h1>
    <form action="{{ route('search') }}" method="get" class="mt-6 flex gap-3">
        <input type="search" name="q" value="{{ $q }}" class="w-full max-w-xl rounded-2xl border-slate-200 px-4 py-3" placeholder="{{ __('Search hosting...') }}">
        <button class="btn-primary">Search</button>
    </form>

    @if ($q !== '')
        <div class="mt-10 space-y-10">
            <section>
                <h2 class="mb-4 text-xl font-semibold">Companies ({{ $companies->count() }})</h2>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($companies as $company)
                        <x-company-card :company="$company->loadMissing('features')" />
                    @empty
                        <p class="text-slate-500">{{ __('No results found.') }}</p>
                    @endforelse
                </div>
            </section>
            <section>
                <h2 class="mb-4 text-xl font-semibold">Reviews ({{ $reviews->count() }})</h2>
                <div class="space-y-3">
                    @foreach ($reviews as $review)
                        <a href="{{ route('companies.show', $review->company) }}" class="card-surface block p-4 hover:border-brand-300">
                            <div class="font-semibold">{{ $review->title }}</div>
                            <div class="text-sm text-slate-500">{{ $review->company?->name }} · {{ number_format($review->rating_overall, 1) }}/5</div>
                        </a>
                    @endforeach
                </div>
            </section>
            <section>
                <h2 class="mb-4 text-xl font-semibold">Pages ({{ $pages->count() }})</h2>
                <div class="space-y-3">
                    @foreach ($pages as $page)
                        <a href="{{ route('pages.show', $page->slug) }}" class="card-surface block p-4 hover:border-brand-300">
                            <div class="font-semibold">{{ t_field($page, 'title') }}</div>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>
    @endif
</div>
@endsection
