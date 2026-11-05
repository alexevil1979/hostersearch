@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach ($stats as $label => $value)
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ str_replace('_', ' ', $label) }}</div>
            <div class="mt-2 text-3xl font-bold text-brand-900">{{ number_format($value) }}</div>
        </div>
    @endforeach
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-semibold">Latest reviews</h2>
            <a href="{{ route('admin.reviews.index') }}" class="text-sm text-brand-700">View all</a>
        </div>
        <ul class="space-y-3 text-sm">
            @foreach ($latestReviews as $review)
                <li class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                    <span>{{ $review->company?->name }} — {{ \Illuminate\Support\Str::limit($review->title, 40) }}</span>
                    <span class="text-slate-400">{{ $review->status->value }}</span>
                </li>
            @endforeach
        </ul>
    </section>
    <section class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-semibold">Latest claims</h2>
            <a href="{{ route('admin.claims.index') }}" class="text-sm text-brand-700">View all</a>
        </div>
        <ul class="space-y-3 text-sm">
            @foreach ($latestClaims as $claim)
                <li class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                    <span>{{ $claim->company?->name }} — {{ $claim->full_name }}</span>
                    <span class="text-slate-400">{{ $claim->status->value }}</span>
                </li>
            @endforeach
        </ul>
    </section>
</div>
@endsection
