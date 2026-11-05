<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Comparison;
use App\Models\Coupon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();
        $payload = Cache::remember("home.payload.{$locale}", 300, function () {
            return [
                'recommended' => Company::query()
                    ->recommended()
                    ->with(['features', 'types'])
                    ->orderByDesc('overall_score')
                    ->limit(8)
                    ->get(),
                'categories' => Category::query()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(),
                'comparisons' => Comparison::query()
                    ->where('is_published', true)
                    ->where('is_popular', true)
                    ->with('companies')
                    ->orderByDesc('view_count')
                    ->limit(6)
                    ->get(),
                'coupons' => Coupon::query()
                    ->active()
                    ->exclusive()
                    ->with('company')
                    ->orderBy('sort_order')
                    ->limit(6)
                    ->get(),
            ];
        });

        return view('public.home', $payload);
    }
}
