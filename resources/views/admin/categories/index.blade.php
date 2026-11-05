@extends('layouts.admin')
@section('title', 'Categories')
@section('heading', 'Categories')

@section('content')
<form method="post" action="{{ route('admin.categories.store') }}" class="mb-6 grid gap-3 rounded-2xl border border-slate-200 bg-white p-5 md:grid-cols-3">
    @csrf
    <input name="name" required placeholder="Name" class="rounded-xl border-slate-200">
    <input name="slug" placeholder="slug" class="rounded-xl border-slate-200">
    <select name="type" class="rounded-xl border-slate-200">
        @foreach (['hosting','domain','builder','ai','other'] as $type)
            <option value="{{ $type }}">{{ $type }}</option>
        @endforeach
    </select>
    <input name="name_ru" placeholder="Name RU" class="rounded-xl border-slate-200">
    <input type="number" name="sort_order" value="0" class="rounded-xl border-slate-200">
    <button class="rounded-xl bg-brand-700 px-4 py-2 text-sm font-semibold text-white">Add</button>
</form>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
<table class="min-w-full text-sm">
    <thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-3 text-left">Name</th><th class="px-3 py-3">Slug</th><th class="px-3 py-3">Type</th><th class="px-3 py-3"></th></tr></thead>
    <tbody>
    @foreach ($categories as $category)
        <tr class="border-t">
            <td class="px-3 py-3">{{ $category->name }}</td>
            <td class="px-3 py-3">{{ $category->slug }}</td>
            <td class="px-3 py-3">{{ $category->type->value }}</td>
            <td class="px-3 py-3 text-right">
                <form method="post" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                    <button class="text-rose-600">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
