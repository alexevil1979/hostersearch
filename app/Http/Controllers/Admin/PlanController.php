<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function store(Request $request, Company $company): RedirectResponse
    {
        $data = $this->validated($request);
        $company->plans()->create([
            ...$data,
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('status', 'Plan created.');
    }

    public function update(Request $request, Company $company, Plan $plan): RedirectResponse
    {
        abort_unless($plan->company_id === $company->id, 404);
        $data = $this->validated($request);
        $plan->update([
            ...$data,
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Plan updated.');
    }

    public function destroy(Company $company, Plan $plan): RedirectResponse
    {
        abort_unless($plan->company_id === $company->id, 404);
        $plan->delete();

        return back()->with('status', 'Plan deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_renewal' => ['nullable', 'numeric', 'min:0'],
            'space_gb' => ['nullable', 'integer', 'min:0'],
            'cpu' => ['nullable', 'string', 'max:80'],
            'ram' => ['nullable', 'string', 'max:80'],
            'sites' => ['nullable', 'integer', 'min:0'],
            'bandwidth' => ['nullable', 'string', 'max:80'],
            'os' => ['nullable', 'string', 'max:80'],
            'panel' => ['nullable', 'string', 'max:80'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'affiliate_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
