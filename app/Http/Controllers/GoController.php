<?php

namespace App\Http\Controllers;

use App\Models\ClickLog;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoController extends Controller
{
    public function __invoke(Request $request, Company $company): RedirectResponse
    {
        abort_unless(in_array($company->status->value, ['approved', 'claimed'], true), 404);

        $plan = null;
        $coupon = null;

        if ($request->filled('plan')) {
            $plan = Plan::query()
                ->where('company_id', $company->id)
                ->where('slug', $request->input('plan'))
                ->first();
        }

        if ($request->filled('coupon')) {
            $coupon = Coupon::query()
                ->where('company_id', $company->id)
                ->where('id', $request->input('coupon'))
                ->first();
        }

        $destination = $coupon?->affiliate_url
            ?: $plan?->affiliate_url
            ?: $company->affiliate_url
            ?: $company->website
            ?: url('/');

        ClickLog::query()->create([
            'company_id' => $company->id,
            'plan_id' => $plan?->id,
            'coupon_id' => $coupon?->id,
            'user_id' => $request->user()?->id,
            'source' => $request->input('src', $coupon ? 'coupon' : ($plan ? 'plan' : 'visit')),
            'referrer' => substr((string) $request->headers->get('referer'), 0, 500),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'destination_url' => $destination,
        ]);

        $company->increment('click_count');
        if ($coupon) {
            $coupon->increment('clicks');
        }

        return redirect()->away($destination);
    }
}
