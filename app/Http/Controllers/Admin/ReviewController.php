<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::query()->with(['company', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $reviews = $query->paginate(25)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => ReviewStatus::Approved]);
        $review->company?->update([
            'review_count' => $review->company->reviews()->where('status', ReviewStatus::Approved)->count(),
        ]);
        ActivityLog::record('review.approved', $review);

        return back()->with('status', 'Review approved.');
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update(['status' => ReviewStatus::Rejected]);
        ActivityLog::record('review.rejected', $review);

        return back()->with('status', 'Review rejected.');
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);

        ReviewReply::query()->create([
            'review_id' => $review->id,
            'user_id' => $request->user()->id,
            'company_id' => $review->company_id,
            'body' => $data['body'],
            'is_official' => true,
        ]);

        return back()->with('status', 'Reply posted.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $company = $review->company;
        $review->delete();
        if ($company) {
            $company->update([
                'review_count' => $company->reviews()->where('status', ReviewStatus::Approved)->count(),
            ]);
        }

        return back()->with('status', 'Review deleted.');
    }
}
