<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category): View
    {
        abort_unless($category->is_active, 404);

        $query = Company::query()
            ->approved()
            ->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id))
            ->with(['features', 'types', 'plans' => fn ($q) => $q->where('is_active', true)->orderBy('price')]);

        if ($request->filled('min_price')) {
            $query->where('price_from', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price_from', '<=', (float) $request->input('max_price'));
        }
        if ($request->filled('min_score')) {
            $query->where('overall_score', '>=', (float) $request->input('min_score'));
        }
        if ($request->filled('type')) {
            $query->whereHas('types', fn ($q) => $q->where('slug', $request->input('type')));
        }
        if ($request->filled('os')) {
            $query->whereHas('plans', fn ($q) => $q->where('os', 'like', '%'.$request->input('os').'%'));
        }
        if ($request->filled('panel')) {
            $query->whereHas('plans', fn ($q) => $q->where('panel', 'like', '%'.$request->input('panel').'%'));
        }
        if ($request->filled('location')) {
            $query->whereHas('locations', fn ($q) => $q->where('country_code', strtoupper($request->input('location'))));
        }

        $sort = $request->input('sort', 'score');
        match ($sort) {
            'price_asc' => $query->orderBy('price_from'),
            'price_desc' => $query->orderByDesc('price_from'),
            'reviews' => $query->orderByDesc('review_count'),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('overall_score')->orderBy('sort_order'),
        };

        $companies = $query->paginate(12)->withQueryString();
        $types = CompanyType::query()->orderBy('sort_order')->get();

        return view('public.category', compact('category', 'companies', 'types', 'sort'));
    }
}
