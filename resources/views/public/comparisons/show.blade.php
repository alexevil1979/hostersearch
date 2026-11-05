@extends('layouts.public')

@section('title', ($comparison?->title ?? $companies->pluck('name')->join(' vs ')).' — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ $comparison ? t_field($comparison, 'title') : $companies->pluck('name')->join(' vs ') }}</h1>
    @if ($comparison)
        <p class="mt-3 max-w-3xl text-slate-600">{{ t_field($comparison, 'description') }}</p>
    @endif

    <div class="mt-8 overflow-x-auto card-surface">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-4 py-3 text-left font-medium text-slate-500">Metric</th>
                    @foreach ($companies as $company)
                        <th class="px-4 py-3 text-left">
                            <a href="{{ route('companies.show', $company) }}" class="font-semibold text-brand-900 hover:underline">{{ $company->name }}</a>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-slate-50">
                    <td class="px-4 py-3 text-slate-500">Score</td>
                    @foreach ($companies as $company)
                        <td class="px-4 py-3"><span class="score-badge">{{ number_format($company->overall_score, 1) }}</span></td>
                    @endforeach
                </tr>
                <tr class="border-b border-slate-50">
                    <td class="px-4 py-3 text-slate-500">Price from</td>
                    @foreach ($companies as $company)
                        <td class="px-4 py-3 font-semibold">{{ money($company->price_from) }}</td>
                    @endforeach
                </tr>
                <tr class="border-b border-slate-50">
                    <td class="px-4 py-3 text-slate-500">Reviews</td>
                    @foreach ($companies as $company)
                        <td class="px-4 py-3">{{ $company->review_count }}</td>
                    @endforeach
                </tr>
                <tr class="border-b border-slate-50">
                    <td class="px-4 py-3 text-slate-500">Types</td>
                    @foreach ($companies as $company)
                        <td class="px-4 py-3">{{ $company->types->pluck('name')->join(', ') }}</td>
                    @endforeach
                </tr>
                <tr class="border-b border-slate-50">
                    <td class="px-4 py-3 text-slate-500">Top plan</td>
                    @foreach ($companies as $company)
                        @php $plan = $company->plans->first(); @endphp
                        <td class="px-4 py-3">{{ $plan ? t_field($plan, 'name').' · '.money($plan->price) : '—' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-4 py-3 text-slate-500"></td>
                    @foreach ($companies as $company)
                        <td class="px-4 py-3">
                            <a href="{{ route('go', $company) }}" rel="nofollow sponsored" class="btn-accent !py-1.5 !px-3 text-xs">{{ __('Visit Site') }}</a>
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
