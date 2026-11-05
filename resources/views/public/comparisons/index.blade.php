@extends('layouts.public')

@section('title', __('Compare').' — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ __('Popular comparisons') }}</h1>

    <form method="post" action="{{ route('comparisons.build') }}" class="card-surface mt-8 p-6">
        @csrf
        <h2 class="font-semibold text-brand-900">{{ __('Build comparison') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('Select companies') }} (2–3)</p>
        <div class="mt-4 grid gap-3 sm:grid-cols-3">
            @for ($i = 0; $i < 3; $i++)
                <select name="companies[]" class="rounded-xl border-slate-200" @if($i < 2) required @endif>
                    <option value="">—</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->slug }}">{{ $company->name }} ({{ number_format($company->overall_score, 1) }})</option>
                    @endforeach
                </select>
            @endfor
        </div>
        <button class="btn-primary mt-4">{{ __('Compare') }}</button>
    </form>

    <div class="mt-10 grid gap-4 md:grid-cols-2">
        @foreach ($comparisons as $comparison)
            <a href="{{ route('comparisons.show', $comparison->slug) }}" class="card-surface p-5 transition hover:border-brand-300">
                <div class="font-semibold text-brand-900">{{ t_field($comparison, 'title') }}</div>
                <p class="mt-2 text-sm text-slate-600">{{ t_field($comparison, 'description') }}</p>
            </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $comparisons->links() }}</div>
</div>
@endsection
