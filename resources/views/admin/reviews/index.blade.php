@extends('layouts.admin')
@section('title', 'Reviews')
@section('heading', 'Reviews moderation')

@section('content')
<form method="get" class="mb-4 flex gap-2">
    <select name="status" class="rounded-xl border-slate-200 text-sm">
        <option value="">All statuses</option>
        @foreach (['pending','approved','rejected'] as $s)
            <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
        @endforeach
    </select>
    <select name="type" class="rounded-xl border-slate-200 text-sm">
        <option value="">All types</option>
        <option value="user" @selected(request('type')==='user')>user</option>
        <option value="expert" @selected(request('type')==='expert')>expert</option>
    </select>
    <button class="rounded-xl bg-brand-800 px-4 py-2 text-sm text-white">Filter</button>
</form>

<div class="space-y-4">
@foreach ($reviews as $review)
    <article class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="font-semibold">{{ $review->title }} <span class="text-slate-400">· {{ number_format($review->rating_overall,1) }}/5</span></div>
                <div class="text-sm text-slate-500">{{ $review->company?->name }} · {{ $review->type->value }} · {{ $review->status->value }} · {{ $review->created_at?->diffForHumans() }}</div>
            </div>
            <div class="flex flex-wrap gap-2">
                <form method="post" action="{{ route('admin.reviews.approve', $review) }}">@csrf<button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">Approve</button></form>
                <form method="post" action="{{ route('admin.reviews.reject', $review) }}">@csrf<button class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white">Reject</button></form>
                <form method="post" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white">Delete</button></form>
            </div>
        </div>
        <p class="mt-3 text-sm text-slate-700">{{ $review->body }}</p>
        <form method="post" action="{{ route('admin.reviews.reply', $review) }}" class="mt-4 flex gap-2">
            @csrf
            <input name="body" required placeholder="Official reply..." class="w-full rounded-xl border-slate-200 text-sm">
            <button class="rounded-xl border border-slate-200 px-3 py-2 text-sm">Reply</button>
        </form>
    </article>
@endforeach
</div>
<div class="mt-4">{{ $reviews->links() }}</div>
@endsection
