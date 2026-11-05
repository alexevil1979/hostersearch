<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()
            ->active()
            ->with('company')
            ->orderByDesc('exclusive')
            ->orderBy('sort_order')
            ->paginate(18);

        return view('public.coupons', compact('coupons'));
    }
}
