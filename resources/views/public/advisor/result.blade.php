@extends('layouts.public')

@section('title', __('Your matches').' — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ __('Your matches') }}</h1>
    <p class="mt-2 text-slate-600">Based on your answers we recommend these providers.</p>
    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($companies as $company)
            <div>
                <div class="mb-2 text-xs font-semibold uppercase text-brand-600">Match score {{ number_format($scores[$company->id] ?? $company->overall_score, 1) }}</div>
                <x-company-card :company="$company" />
            </div>
        @endforeach
    </div>
    <a href="{{ route('advisor.index') }}" class="btn-secondary mt-8 inline-flex">Try again</a>
</div>
@endsection
