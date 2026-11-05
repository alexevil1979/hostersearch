@extends('layouts.admin')
@section('title', 'Settings')
@section('heading', 'Site settings')

@section('content')
<form method="post" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
    @csrf @method('PUT')
    @foreach ($settings as $key => $value)
        <div>
            <label class="mb-1 block text-sm font-medium">{{ str_replace('_', ' ', $key) }}</label>
            <input name="{{ $key }}" value="{{ old($key, is_array($value) ? json_encode($value) : $value) }}" class="w-full rounded-xl border-slate-200">
        </div>
    @endforeach
    <button class="rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white">Save settings</button>
</form>
@endsection
