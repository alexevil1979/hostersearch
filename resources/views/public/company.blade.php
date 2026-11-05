@extends('layouts.public')

@section('title', t_field($company, 'name').' Reviews — HosterSearch')
@section('meta_description', t_field($company, 'short_description'))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $company->name,
    'url' => $company->website,
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => $company->overall_score,
        'reviewCount' => max(1, $company->review_count),
        'bestRating' => 5,
        'worstRating' => 1,
    ],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<div class="border-b border-slate-200 bg-white/70">
    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div class="flex items-start gap-4">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-white shadow-soft">
                @if ($company->logo)
                    <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain" loading="lazy">
                @else
                    <span class="text-lg font-bold text-brand-800">{{ strtoupper(substr($company->name, 0, 2)) }}</span>
                @endif
            </div>
            <div>
            <h1 class="font-display text-4xl font-semibold text-brand-950">{{ t_field($company, 'name') }}</h1>
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <x-stars :score="$company->overall_score" size="lg" />
                <span class="score-badge text-base">{{ number_format($company->overall_score, 1) }}/5</span>
                <span class="text-sm text-slate-500">{{ $company->review_count }} {{ __('reviews') }}</span>
                @if ($company->founded)
                    <span class="text-sm text-slate-500">Est. {{ $company->founded }} · {{ $company->headquarters }}</span>
                @endif
            </div>
            <p class="mt-4 max-w-2xl text-slate-600">{{ t_field($company, 'short_description') }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('go', $company) }}" rel="nofollow sponsored" class="btn-accent">{{ __('Visit Site') }}</a>
            <a href="{{ route('claim.create', ['company_id' => $company->id]) }}" class="btn-secondary">{{ __('Claim listing') }}</a>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl space-y-12 px-4 py-10 sm:px-6 lg:px-8">
    <section class="grid gap-8 lg:grid-cols-[1.4fr_0.8fr]">
        <div class="card-surface p-6 sm:p-8">
            <h2 class="text-2xl font-semibold text-brand-900">{{ __('Expert Review') }}</h2>
            <div class="prose-host mt-4 space-y-4 text-slate-700 leading-relaxed">
                {!! nl2br(e(t_field($company, 'expert_review') ?: $expertReview?->body)) !!}
            </div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-emerald-50 p-4">
                    <div class="font-semibold text-emerald-800">{{ __('Pros') }}</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-emerald-900">
                        @foreach ((t_field($company, 'pros') ?: []) as $pro)
                            <li>{{ $pro }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-xl bg-rose-50 p-4">
                    <div class="font-semibold text-rose-800">{{ __('Cons') }}</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-900">
                        @foreach ((t_field($company, 'cons') ?: []) as $con)
                            <li>{{ $con }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-surface p-6">
            <h3 class="font-semibold text-brand-900">Rating breakdown</h3>
            @php $breakdown = $company->rating_breakdown ?? []; @endphp
            <div class="mt-4 space-y-3">
                @foreach (['pricing','features','performance','ease','support'] as $key)
                    @php $val = (float) ($breakdown[$key] ?? $company->overall_score); @endphp
                    <div>
                        <div class="mb-1 flex justify-between text-sm"><span class="capitalize text-slate-600">{{ $key }}</span><span class="font-semibold">{{ number_format($val, 1) }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-brand-600" style="width: {{ min(100, $val / 5 * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($company->features->isNotEmpty())
                <h3 class="mt-8 font-semibold text-brand-900">Features</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($company->features as $feature)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ t_field($feature, 'name') }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section>
        <h2 class="section-title mb-6">{{ __('Plans & pricing') }}</h2>
        <div class="overflow-x-auto card-surface">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Plan</th>
                        <th class="px-4 py-3 font-medium">Price</th>
                        <th class="px-4 py-3 font-medium">Storage</th>
                        <th class="px-4 py-3 font-medium">Sites</th>
                        <th class="px-4 py-3 font-medium">Panel</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($company->plans as $plan)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold text-brand-900">{{ t_field($plan, 'name') }}</td>
                            <td class="px-4 py-3">{{ money($plan->price) }}/mo</td>
                            <td class="px-4 py-3">{{ $plan->space_gb ? $plan->space_gb.' GB' : 'Unlimited' }}</td>
                            <td class="px-4 py-3">{{ $plan->sites ?? 'Unlimited' }}</td>
                            <td class="px-4 py-3">{{ $plan->panel }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('go', ['company' => $company->slug, 'plan' => $plan->slug]) }}" rel="nofollow sponsored" class="btn-accent !py-1.5 !px-3 text-xs">{{ __('Visit Site') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @if ($company->coupons->isNotEmpty())
        <section>
            <h2 class="section-title mb-6">{{ __('Coupons') }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($company->coupons as $coupon)
                    <div class="card-surface flex items-center justify-between gap-4 p-5">
                        <div>
                            <div class="font-semibold">{{ t_field($coupon, 'title') }}</div>
                            <div class="mt-1 font-mono text-sm text-brand-700">{{ $coupon->code }} · {{ $coupon->discount }}</div>
                        </div>
                        <a href="{{ route('go', ['company' => $company->slug, 'coupon' => $coupon->id]) }}" rel="nofollow sponsored" class="btn-primary !py-2">{{ __('Get deal') }}</a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <div>
            <h2 class="section-title mb-6">{{ __('User Reviews') }}</h2>
            <div class="space-y-4">
                @forelse ($userReviews as $review)
                    <article class="card-surface p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-semibold text-brand-900">{{ $review->title }}</div>
                            <span class="score-badge">{{ number_format($review->rating_overall, 1) }}</span>
                        </div>
                        <div class="mt-1 text-xs text-slate-500">{{ $review->author_name ?? $review->user?->name }} · {{ $review->created_at?->diffForHumans() }}</div>
                        <p class="mt-3 text-sm leading-relaxed text-slate-700">{{ $review->body }}</p>
                        @foreach ($review->replies as $reply)
                            <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
                                <span class="font-semibold text-brand-800">Official reply:</span> {{ $reply->body }}
                            </div>
                        @endforeach
                    </article>
                @empty
                    <div class="card-surface p-6 text-slate-500">{{ __('No results found.') }}</div>
                @endforelse
            </div>
            <div class="mt-6">{{ $userReviews->links() }}</div>
        </div>

        <div class="card-surface h-fit p-6">
            <h3 class="text-lg font-semibold text-brand-900">{{ __('Write a review') }}</h3>
            @auth
                <form method="post" action="{{ route('companies.reviews.store', $company) }}" class="mt-4 space-y-3 text-sm">
                    @csrf
                    <div>
                        <label class="mb-1 block font-medium">Overall</label>
                        <input type="number" name="rating_overall" min="1" max="5" step="0.1" required value="{{ old('rating_overall', 5) }}" class="w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Title</label>
                        <input type="text" name="title" required value="{{ old('title') }}" class="w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Review</label>
                        <textarea name="body" rows="5" required class="w-full rounded-xl border-slate-200">{{ old('body') }}</textarea>
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">{{ __('Pros') }} (one per line)</label>
                        <textarea name="pros" rows="2" class="w-full rounded-xl border-slate-200">{{ old('pros') }}</textarea>
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">{{ __('Cons') }} (one per line)</label>
                        <textarea name="cons" rows="2" class="w-full rounded-xl border-slate-200">{{ old('cons') }}</textarea>
                    </div>
                    <button class="btn-primary w-full">{{ __('Submit review') }}</button>
                </form>
            @else
                <p class="mt-3 text-sm text-slate-600">Please <a class="font-semibold text-brand-700 underline" href="{{ route('login') }}">{{ __('Login') }}</a> to write a review.</p>
            @endauth
        </div>
    </section>

    @if ($similar->isNotEmpty())
        <section>
            <h2 class="section-title mb-6">{{ __('Similar providers') }}</h2>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($similar as $item)
                    <x-company-card :company="$item" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
