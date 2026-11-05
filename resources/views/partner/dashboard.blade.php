@extends('layouts.public')

@section('title', 'Partner — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="section-title">{{ __('Partner') }} dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-secondary !py-2">{{ __('Logout') }}</button></form>
    </div>

    @if ($company)
        <div class="card-surface mb-8 p-6">
            <div class="text-sm text-slate-500">Claimed company</div>
            <div class="mt-1 text-2xl font-semibold text-brand-900">{{ $company->name }}</div>
            <a href="{{ route('companies.show', $company) }}" class="mt-3 inline-flex text-sm font-semibold text-brand-700 hover:underline">View public page →</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $label => $value)
                <div class="card-surface p-5">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ str_replace('_', ' ', $label) }}</div>
                    <div class="mt-2 text-3xl font-bold text-brand-900">{{ number_format($value) }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card-surface p-8 text-center">
            <p class="text-slate-600">No claimed company yet.</p>
            <a href="{{ route('claim.create') }}" class="btn-primary mt-4 inline-flex">{{ __('Claim listing') }}</a>
        </div>
    @endif
</div>
@endsection
