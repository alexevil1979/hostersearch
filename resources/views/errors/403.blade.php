@extends('layouts.public')

@section('title', 'Forbidden — HosterSearch')

@section('content')
<div class="mx-auto max-w-xl px-4 py-24 text-center">
    <div class="font-display text-7xl font-semibold text-brand-800">403</div>
    <h1 class="mt-4 text-2xl font-semibold text-brand-900">Access denied</h1>
    <p class="mt-3 text-slate-600">You do not have permission to view this page.</p>
    <a href="{{ route('home') }}" class="btn-primary mt-8 inline-flex">Go home</a>
</div>
@endsection
