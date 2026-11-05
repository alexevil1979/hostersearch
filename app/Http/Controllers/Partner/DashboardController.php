<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\ClickLog;
use App\Models\Company;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $company = Company::query()
            ->where('claimed_by_user_id', auth()->id())
            ->first();

        $stats = [
            'views' => $company?->view_count ?? 0,
            'clicks' => $company?->click_count ?? 0,
            'reviews' => $company?->review_count ?? 0,
            'clicks_week' => $company
                ? ClickLog::query()->where('company_id', $company->id)->where('created_at', '>=', now()->subWeek())->count()
                : 0,
        ];

        return view('partner.dashboard', compact('company', 'stats'));
    }
}
