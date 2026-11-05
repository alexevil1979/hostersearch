@extends('layouts.public')

@section('title', __('Claim listing').' — HosterSearch')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="section-title">{{ __('Claim listing') }}</h1>
    <p class="mt-3 text-slate-600">Submit a claim to manage your company profile, reply to reviews and access analytics.</p>

    @auth
        <form method="post" action="{{ route('claim.store') }}" class="card-surface mt-8 space-y-4 p-6">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium">Company</label>
                <select name="company_id" required class="w-full rounded-xl border-slate-200">
                    <option value="">Select…</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" @selected($selected == $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Full name</label>
                <input type="text" name="full_name" required value="{{ old('full_name', auth()->user()->name) }}" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Work email</label>
                <input type="email" name="email" required value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Job title</label>
                <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Message</label>
                <textarea name="message" rows="4" class="w-full rounded-xl border-slate-200">{{ old('message') }}</textarea>
            </div>
            <button class="btn-primary">Submit claim</button>
        </form>
    @else
        <div class="card-surface mt-8 p-6">
            Please <a href="{{ route('login') }}" class="font-semibold text-brand-700 underline">{{ __('Login') }}</a> or
            <a href="{{ route('register') }}" class="font-semibold text-brand-700 underline">{{ __('Register') }}</a> to claim a listing.
        </div>
    @endauth
</div>
@endsection
