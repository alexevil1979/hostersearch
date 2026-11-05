@extends('layouts.public')

@section('title', 'Server error — HosterSearch')

@section('content')
<div class="mx-auto max-w-xl px-4 py-24 text-center">
    <div class="font-display text-7xl font-semibold text-brand-800">500</div>
    <h1 class="mt-4 text-2xl font-semibold text-brand-900">Something went wrong</h1>
    <p class="mt-3 text-slate-600">We logged the error. Please try again in a moment.</p>
    <a href="{{ route('home') }}" class="btn-primary mt-8 inline-flex">Go home</a>
</div>
@endsection
