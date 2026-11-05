@props(['company'])

<article class="card-surface group flex h-full flex-col p-5 transition duration-300 hover:-translate-y-1 hover:border-brand-200">
    <div class="flex items-start justify-between gap-3">
        <div class="flex min-w-0 items-start gap-3">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-brand-50 text-sm font-bold text-brand-800">
                @if ($company->logo)
                    <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain" loading="lazy">
                @else
                    {{ strtoupper(substr($company->name, 0, 2)) }}
                @endif
            </div>
            <div class="min-w-0">
                <a href="{{ route('companies.show', $company) }}" class="block truncate text-lg font-semibold text-brand-900 group-hover:text-brand-700">
                    {{ t_field($company, 'name') }}
                </a>
                <div class="mt-1 flex items-center gap-2">
                    <x-stars :score="$company->overall_score" />
                    <span class="score-badge">{{ number_format($company->overall_score, 1) }}</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">{{ $company->review_count }} {{ __('reviews') }}</p>
            </div>
        </div>
        <div class="rounded-xl bg-brand-50 px-3 py-2 text-center">
            <div class="text-[10px] uppercase tracking-wide text-slate-500">{{ __('From') }}</div>
            <div class="text-sm font-bold text-brand-800">{{ money($company->price_from) }}</div>
        </div>
    </div>

    <p class="mt-3 line-clamp-2 flex-1 text-sm text-slate-600">{{ t_field($company, 'short_description') }}</p>

    @if ($company->relationLoaded('features') && $company->features->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-1.5">
            @foreach ($company->features->take(4) as $feature)
                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600">{{ t_field($feature, 'name') }}</span>
            @endforeach
        </div>
    @endif

    <div class="mt-5 flex gap-2">
        <a href="{{ route('go', ['company' => $company->slug, 'src' => 'card']) }}" rel="nofollow sponsored" class="btn-accent flex-1 !py-2 text-center">{{ __('Visit Site') }}</a>
        <a href="{{ route('companies.show', $company) }}" class="btn-secondary !px-3 !py-2">Review</a>
    </div>
</article>
