@extends('layouts.public')

@section('title', 'Page not found — HosterSearch')

@section('content')
<div class="mx-auto max-w-xl px-4 py-24 text-center">
    <div class="font-display text-7xl font-semibold text-brand-800">404</div>
    <h1 class="mt-4 text-2xl font-semibold text-brand-900">Page not found</h1>
    <p class="mt-3 text-slate-600">The page you requested does not exist or was moved.</p>
    <div class="mt-8 flex justify-center gap-3">
        <a href="{{ route('home') }}" class="btn-primary">Go home</a>
        <a href="{{ route('search') }}" class="btn-secondary">Search hosting</a>
    </div>
</div>
@endsection
