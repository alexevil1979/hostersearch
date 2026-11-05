<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Comparison;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComparisonController extends Controller
{
    public function index(): View
    {
        $comparisons = Comparison::query()
            ->where('is_published', true)
            ->with('companies')
            ->orderByDesc('is_popular')
            ->orderByDesc('view_count')
            ->paginate(12);

        $companies = Company::query()->approved()->orderBy('name')->get(['id', 'slug', 'name', 'overall_score']);

        return view('public.comparisons.index', compact('comparisons', 'companies'));
    }

    public function show(string $slugs): View
    {
        // Prefer curated comparison
        $comparison = Comparison::query()
            ->where('slug', $slugs)
            ->where('is_published', true)
            ->with(['companies.plans', 'companies.features', 'companies.types'])
            ->first();

        if ($comparison) {
            $comparison->increment('view_count');
            $companies = $comparison->companies;

            return view('public.comparisons.show', compact('comparison', 'companies'));
        }

        // Dynamic {slug1}-vs-{slug2}[-vs-{slug3}]
        $parts = preg_split('/-vs-/', $slugs) ?: [];
        $parts = array_values(array_filter(array_map('trim', $parts)));
        abort_if(count($parts) < 2 || count($parts) > 3, 404);

        $companies = Company::query()
            ->approved()
            ->whereIn('slug', $parts)
            ->with(['plans', 'features', 'types'])
            ->get()
            ->sortBy(fn ($c) => array_search($c->slug, $parts, true))
            ->values();

        abort_if($companies->count() !== count($parts), 404);

        $comparison = null;

        return view('public.comparisons.show', compact('comparison', 'companies'));
    }

    public function build(Request $request)
    {
        $slugs = collect($request->input('companies', []))
            ->filter()
            ->unique()
            ->take(3)
            ->values();

        abort_if($slugs->count() < 2, 422, 'Select at least 2 companies');

        return redirect()->route('comparisons.show', $slugs->implode('-vs-'));
    }
}
