@extends('layouts.public')

@section('title', __('Coupons').' — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ __('Exclusive coupons') }}</h1>
    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($coupons as $coupon)
            <article class="card-surface p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm text-slate-500">{{ $coupon->company?->name }}</div>
                        <h2 class="mt-1 text-lg font-semibold text-brand-900">{{ t_field($coupon, 'title') }}</h2>
                    </div>
                    @if ($coupon->exclusive)
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-[11px] font-bold uppercase text-amber-800">Exclusive</span>
                    @endif
                </div>
                <p class="mt-3 text-sm text-slate-600">{{ t_field($coupon, 'description') }}</p>
                <div class="mt-4 flex items-center justify-between gap-3">
                    <div>
                        <div class="text-xs uppercase text-slate-400">{{ __('Code') }}</div>
                        <div class="font-mono font-bold text-brand-700">{{ $coupon->code }}</div>
                    </div>
                    <a href="{{ route('go', ['company' => $coupon->company->slug, 'coupon' => $coupon->id]) }}" rel="nofollow sponsored" class="btn-accent !py-2">{{ __('Get deal') }}</a>
                </div>
                @if ($coupon->expires_at)
                    <div class="mt-3 text-xs text-slate-400">{{ __('Expires') }}: {{ $coupon->expires_at->format('Y-m-d') }}</div>
                @endif
            </article>
        @endforeach
    </div>
    <div class="mt-8">{{ $coupons->links() }}</div>
</div>
@endsection
