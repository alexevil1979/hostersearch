@extends('layouts.public')

@section('title', 'Admin — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="section-title">{{ __('Admin') }} dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-secondary !py-2">{{ __('Logout') }}</button></form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $label => $value)
            <div class="card-surface p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ str_replace('_', ' ', $label) }}</div>
                <div class="mt-2 text-3xl font-bold text-brand-900">{{ number_format($value) }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-10 grid gap-8 lg:grid-cols-2">
        <section class="card-surface p-6">
            <h2 class="font-semibold text-brand-900">Latest reviews</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @foreach ($latestReviews as $review)
                    <li class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                        <span>{{ $review->company?->name }} — {{ $review->title }}</span>
                        <span class="text-slate-400">{{ $review->status->value }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
        <section class="card-surface p-6">
            <h2 class="font-semibold text-brand-900">Latest claims</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @foreach ($latestClaims as $claim)
                    <li class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                        <span>{{ $claim->company?->name }} — {{ $claim->full_name }}</span>
                        <span class="text-slate-400">{{ $claim->status->value }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</div>
@endsection
