<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()->with('company')->latest()->paginate(25);

        return view('admin.coupons.index', [
            'coupons' => $coupons,
            'companies' => Company::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Coupon::query()->create([
            ...$data,
            'exclusive' => $request->boolean('exclusive'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('status', 'Coupon created.');
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $this->validated($request);
        $coupon->update([
            ...$data,
            'exclusive' => $request->boolean('exclusive'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return back()->with('status', 'Coupon deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'code' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:180'],
            'title_ru' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'discount' => ['nullable', 'string', 'max:80'],
            'affiliate_url' => ['nullable', 'url', 'max:255'],
            'expires_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
