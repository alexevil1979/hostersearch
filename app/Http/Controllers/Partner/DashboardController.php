<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\ClickLog;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $company = $this->company();

        $stats = [
            'views' => $company?->view_count ?? 0,
            'clicks' => $company?->click_count ?? 0,
            'reviews' => $company?->review_count ?? 0,
            'clicks_week' => $company
                ? ClickLog::query()->where('company_id', $company->id)->where('created_at', '>=', now()->subWeek())->count()
                : 0,
        ];

        $reviews = $company
            ? Review::query()->where('company_id', $company->id)->approved()->with('replies')->latest()->limit(20)->get()
            : collect();

        $plans = $company?->plans()->orderBy('sort_order')->get() ?? collect();

        $widget = $company
            ? '<a href="'.route('companies.show', $company).'" target="_blank" rel="noopener">'.e($company->name).' — '.number_format($company->overall_score, 1).'/5 on HosterSearch</a>'
            : null;

        return view('partner.dashboard', compact('company', 'stats', 'reviews', 'plans', 'widget'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $company = $this->companyOrFail();

        $data = $request->validate([
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        $company->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $company = $this->companyOrFail();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'price' => ['required', 'numeric', 'min:0'],
            'space_gb' => ['nullable', 'integer', 'min:0'],
            'panel' => ['nullable', 'string', 'max:80'],
        ]);

        $company->plans()->create([
            ...$data,
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
            'is_active' => true,
            'currency' => 'USD',
        ]);

        return back()->with('status', 'Plan added.');
    }

    public function destroyPlan(Plan $plan): RedirectResponse
    {
        $company = $this->companyOrFail();
        abort_unless($plan->company_id === $company->id, 403);
        $plan->delete();

        return back()->with('status', 'Plan deleted.');
    }

    public function replyReview(Request $request, Review $review): RedirectResponse
    {
        $company = $this->companyOrFail();
        abort_unless($review->company_id === $company->id, 403);

        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);

        ReviewReply::query()->create([
            'review_id' => $review->id,
            'user_id' => $request->user()->id,
            'company_id' => $company->id,
            'body' => $data['body'],
            'is_official' => true,
        ]);

        return back()->with('status', 'Reply posted.');
    }

    private function company(): ?Company
    {
        return Company::query()->where('claimed_by_user_id', auth()->id())->first();
    }

    private function companyOrFail(): Company
    {
        return $this->company() ?? abort(403, 'No claimed company');
    }
}
