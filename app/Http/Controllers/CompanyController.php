<?php

namespace App\Http\Controllers;

use App\Enums\ReviewStatus;
use App\Enums\ReviewType;
use App\Models\Company;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function show(Company $company): View
    {
        abort_unless(in_array($company->status->value, ['approved', 'claimed'], true), 404);

        $company->increment('view_count');

        $company->load([
            'features',
            'types',
            'locations',
            'plans' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'coupons' => fn ($q) => $q->active()->orderBy('sort_order'),
        ]);

        $expertReview = $company->reviews()
            ->approved()
            ->where('type', ReviewType::Expert)
            ->latest()
            ->first();

        $userReviews = $company->reviews()
            ->approved()
            ->where('type', ReviewType::User)
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(10, ['*'], 'reviews_page');

        $categoryIds = $company->categories()->pluck('categories.id');

        $similar = Company::query()
            ->approved()
            ->with('features')
            ->where('id', '!=', $company->id)
            ->when($categoryIds->isNotEmpty(), function ($q) use ($categoryIds) {
                $q->whereHas('categories', fn ($qq) => $qq->whereIn('categories.id', $categoryIds));
            })
            ->orderByDesc('overall_score')
            ->limit(4)
            ->get();

        return view('public.company', compact('company', 'expertReview', 'userReviews', 'similar'));
    }

    public function storeReview(Request $request, Company $company): RedirectResponse
    {
        $request->validate([
            'rating_overall' => ['required', 'numeric', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string', 'min:30', 'max:5000'],
            'pros' => ['nullable', 'string', 'max:1000'],
            'cons' => ['nullable', 'string', 'max:1000'],
            'pricing' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'features' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'performance' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'ease' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'support' => ['nullable', 'numeric', 'min:1', 'max:5'],
        ]);

        Review::query()->create([
            'company_id' => $company->id,
            'user_id' => $request->user()->id,
            'type' => ReviewType::User,
            'author_name' => $request->user()->name,
            'author_email' => $request->user()->email,
            'rating_overall' => $request->input('rating_overall'),
            'ratings_json' => [
                'pricing' => (float) $request->input('pricing', $request->input('rating_overall')),
                'features' => (float) $request->input('features', $request->input('rating_overall')),
                'performance' => (float) $request->input('performance', $request->input('rating_overall')),
                'ease' => (float) $request->input('ease', $request->input('rating_overall')),
                'support' => (float) $request->input('support', $request->input('rating_overall')),
            ],
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'pros' => array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('pros'))))),
            'cons' => array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('cons'))))),
            'status' => ReviewStatus::Pending,
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', __('Your review was submitted and is pending moderation.'));
    }
}
