@extends('layouts.public')

@section('title', 'Partner — HosterSearch')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between gap-3">
        <h1 class="section-title">{{ __('Partner') }} dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-secondary !py-2">{{ __('Logout') }}</button></form>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    @if ($company)
        <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $label => $value)
                <div class="card-surface p-5">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ str_replace('_', ' ', $label) }}</div>
                    <div class="mt-2 text-3xl font-bold text-brand-900">{{ number_format($value) }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <form method="post" action="{{ route('partner.profile.update') }}" class="card-surface space-y-3 p-6">
                @csrf @method('PUT')
                <h2 class="text-lg font-semibold text-brand-900">{{ $company->name }} profile</h2>
                <div>
                    <label class="mb-1 block text-sm font-medium">Website</label>
                    <input name="website" value="{{ old('website', $company->website) }}" class="w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Short description</label>
                    <textarea name="short_description" rows="3" class="w-full rounded-xl border-slate-200">{{ old('short_description', $company->short_description) }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Description</label>
                    <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200">{{ old('description', $company->description) }}</textarea>
                </div>
                <button class="btn-primary">Save profile</button>
                <a href="{{ route('companies.show', $company) }}" class="btn-secondary ml-2">Public page</a>
            </form>

            <div class="card-surface p-6">
                <h2 class="text-lg font-semibold text-brand-900">Badge / widget</h2>
                <p class="mt-2 text-sm text-slate-600">Embed this HTML on your site:</p>
                <textarea readonly rows="3" class="mt-3 w-full rounded-xl border-slate-200 font-mono text-xs">{{ $widget }}</textarea>
            </div>
        </div>

        <section class="card-surface mt-8 p-6">
            <h2 class="mb-4 text-lg font-semibold">Plans</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-2 text-left">Name</th><th class="px-3 py-2">Price</th><th class="px-3 py-2"></th></tr></thead>
                    <tbody>
                    @foreach ($plans as $plan)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $plan->name }}</td>
                            <td class="px-3 py-2 text-center">{{ money($plan->price) }}</td>
                            <td class="px-3 py-2 text-right">
                                <form method="post" action="{{ route('partner.plans.destroy', $plan) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button class="text-rose-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <form method="post" action="{{ route('partner.plans.store') }}" class="mt-4 grid gap-2 md:grid-cols-4">
                @csrf
                <input name="name" required placeholder="Plan name" class="rounded-xl border-slate-200">
                <input type="number" step="0.01" name="price" required placeholder="Price" class="rounded-xl border-slate-200">
                <input type="number" name="space_gb" placeholder="GB" class="rounded-xl border-slate-200">
                <button class="btn-primary !py-2">Add plan</button>
            </form>
        </section>

        <section class="mt-8 space-y-4">
            <h2 class="text-lg font-semibold text-brand-900">Reviews</h2>
            @forelse ($reviews as $review)
                <article class="card-surface p-5">
                    <div class="font-semibold">{{ $review->title }} · {{ number_format($review->rating_overall, 1) }}/5</div>
                    <p class="mt-2 text-sm text-slate-700">{{ $review->body }}</p>
                    @foreach ($review->replies as $reply)
                        <div class="mt-2 rounded-xl bg-slate-50 p-3 text-sm">Reply: {{ $reply->body }}</div>
                    @endforeach
                    <form method="post" action="{{ route('partner.reviews.reply', $review) }}" class="mt-3 flex gap-2">
                        @csrf
                        <input name="body" required placeholder="Official reply" class="w-full rounded-xl border-slate-200 text-sm">
                        <button class="btn-secondary !py-2">Reply</button>
                    </form>
                </article>
            @empty
                <div class="card-surface p-5 text-slate-500">No reviews yet.</div>
            @endforelse
        </section>
    @else
        <div class="card-surface p-8 text-center">
            <p class="text-slate-600">No claimed company yet.</p>
            <a href="{{ route('claim.create') }}" class="btn-primary mt-4 inline-flex">{{ __('Claim listing') }}</a>
        </div>
    @endif
</div>
@endsection
