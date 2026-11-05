@extends('layouts.admin')
@section('title', 'Pages')
@section('heading', 'Static pages')

@section('content')
<form method="post" action="{{ route('admin.pages.store') }}" class="mb-6 space-y-3 rounded-2xl border border-slate-200 bg-white p-5">
    @csrf
    <div class="grid gap-3 md:grid-cols-2">
        <input name="title" required placeholder="Title" class="rounded-xl border-slate-200">
        <input name="slug" placeholder="slug" class="rounded-xl border-slate-200">
    </div>
    <textarea name="body" rows="4" placeholder="HTML body" class="w-full rounded-xl border-slate-200"></textarea>
    <button class="rounded-xl bg-brand-700 px-4 py-2 text-sm font-semibold text-white">Add page</button>
</form>

<div class="space-y-4">
@foreach ($pages as $page)
    <form method="post" action="{{ route('admin.pages.update', $page) }}" class="rounded-2xl border border-slate-200 bg-white p-5">
        @csrf @method('PUT')
        <div class="grid gap-3 md:grid-cols-2">
            <input name="title" value="{{ $page->title }}" class="rounded-xl border-slate-200">
            <input name="slug" value="{{ $page->slug }}" class="rounded-xl border-slate-200">
        </div>
        <textarea name="body" rows="5" class="mt-3 w-full rounded-xl border-slate-200">{{ $page->body }}</textarea>
        <div class="mt-3 flex gap-2">
            <button class="rounded-xl bg-brand-800 px-4 py-2 text-sm text-white">Save</button>
            <button form="del-{{ $page->id }}" class="rounded-xl border border-rose-200 px-4 py-2 text-sm text-rose-700">Delete</button>
        </div>
    </form>
    <form id="del-{{ $page->id }}" method="post" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')</form>
@endforeach
</div>
<div class="mt-4">{{ $pages->links() }}</div>
@endsection
