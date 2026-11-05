<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\ClickLog;
use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'companies' => Company::query()->count(),
            'reviews_pending' => Review::query()->where('status', 'pending')->count(),
            'claims_pending' => Claim::query()->where('status', 'pending')->count(),
            'users' => User::query()->count(),
            'clicks_today' => ClickLog::query()->whereDate('created_at', today())->count(),
            'clicks_week' => ClickLog::query()->where('created_at', '>=', now()->subWeek())->count(),
        ];

        $latestReviews = Review::query()->with('company')->latest()->limit(8)->get();
        $latestClaims = Claim::query()->with(['company', 'user'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'latestReviews', 'latestClaims'));
    }
}
