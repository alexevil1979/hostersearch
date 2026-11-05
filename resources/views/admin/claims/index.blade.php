@extends('layouts.admin')
@section('title', 'Claims')
@section('heading', 'Company claims')

@section('content')
<div class="space-y-4">
@foreach ($claims as $claim)
    <article class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="font-semibold">{{ $claim->company?->name }}</div>
                <div class="text-sm text-slate-500">{{ $claim->full_name }} · {{ $claim->email }} · {{ $claim->status->value }}</div>
                <p class="mt-2 text-sm">{{ $claim->message }}</p>
            </div>
            @if ($claim->status->value === 'pending')
                <div class="flex gap-2">
                    <form method="post" action="{{ route('admin.claims.approve', $claim) }}">@csrf
                        <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">Approve</button>
                    </form>
                    <form method="post" action="{{ route('admin.claims.reject', $claim) }}">@csrf
                        <button class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white">Reject</button>
                    </form>
                </div>
            @endif
        </div>
    </article>
@endforeach
</div>
<div class="mt-4">{{ $claims->links() }}</div>
@endsection
