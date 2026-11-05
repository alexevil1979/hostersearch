<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CompanyStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::query()->withCount('plans')->latest();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(fn ($qq) => $qq->where('name', 'like', "%{$q}%")->orWhere('slug', 'like', "%{$q}%"));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $companies = $query->paginate(20)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('admin.companies.form', [
            'company' => new Company(['status' => CompanyStatus::Pending, 'price_currency' => 'USD']),
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'types' => CompanyType::query()->orderBy('sort_order')->get(),
            'features' => Feature::query()->orderBy('sort_order')->get(),
            'selectedCategories' => [],
            'selectedTypes' => [],
            'selectedFeatures' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $company = $this->save($request, new Company);
        ActivityLog::record('company.created', $company);

        return redirect()->route('admin.companies.edit', $company)->with('status', 'Company created.');
    }

    public function edit(Company $company): View
    {
        $company->load(['plans' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.companies.form', [
            'company' => $company,
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'types' => CompanyType::query()->orderBy('sort_order')->get(),
            'features' => Feature::query()->orderBy('sort_order')->get(),
            'selectedCategories' => $company->categories()->pluck('categories.id')->all(),
            'selectedTypes' => $company->types()->pluck('company_types.id')->all(),
            'selectedFeatures' => $company->features()->pluck('features.id')->all(),
        ]);
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $this->save($request, $company);
        ActivityLog::record('company.updated', $company);

        return back()->with('status', 'Company updated.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        ActivityLog::record('company.deleted', $company, ['name' => $company->name]);
        $company->delete();

        return redirect()->route('admin.companies.index')->with('status', 'Company deleted.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:companies,id'],
            'action' => ['required', 'in:approve,feature,unfeature,delete'],
        ]);

        $ids = $data['ids'];
        match ($data['action']) {
            'approve' => Company::query()->whereIn('id', $ids)->update(['status' => CompanyStatus::Approved]),
            'feature' => Company::query()->whereIn('id', $ids)->update(['is_featured' => true]),
            'unfeature' => Company::query()->whereIn('id', $ids)->update(['is_featured' => false]),
            'delete' => Company::query()->whereIn('id', $ids)->delete(),
        };

        ActivityLog::record('company.bulk', null, $data);

        return back()->with('status', 'Bulk action applied.');
    }

    private function save(Request $request, Company $company): Company
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180'],
            'website' => ['nullable', 'url', 'max:255'],
            'affiliate_url' => ['nullable', 'url', 'max:255'],
            'founded' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'headquarters' => ['nullable', 'string', 'max:180'],
            'short_description' => ['nullable', 'string'],
            'short_description_ru' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'description_ru' => ['nullable', 'string'],
            'expert_review' => ['nullable', 'string'],
            'expert_review_ru' => ['nullable', 'string'],
            'overall_score' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,approved,claimed,rejected,suspended'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_recommended' => ['sometimes', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'categories' => ['nullable', 'array'],
            'types' => ['nullable', 'array'],
            'features' => ['nullable', 'array'],
            'pros' => ['nullable', 'string'],
            'cons' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $company->logo = $request->file('logo')->store('logos', 'public');
        }

        $company->fill([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'website' => $data['website'] ?? null,
            'affiliate_url' => $data['affiliate_url'] ?? null,
            'founded' => $data['founded'] ?? null,
            'headquarters' => $data['headquarters'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'short_description_ru' => $data['short_description_ru'] ?? null,
            'description' => $data['description'] ?? null,
            'description_ru' => $data['description_ru'] ?? null,
            'expert_review' => $data['expert_review'] ?? null,
            'expert_review_ru' => $data['expert_review_ru'] ?? null,
            'overall_score' => $data['overall_score'] ?? 0,
            'price_from' => $data['price_from'] ?? null,
            'status' => $data['status'],
            'is_featured' => $request->boolean('is_featured'),
            'is_recommended' => $request->boolean('is_recommended'),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'pros' => $this->linesToArray($data['pros'] ?? null),
            'cons' => $this->linesToArray($data['cons'] ?? null),
        ]);
        $company->save();

        $company->categories()->sync($request->input('categories', []));
        $company->types()->sync($request->input('types', []));
        $featureSync = collect($request->input('features', []))->mapWithKeys(fn ($id) => [$id => ['value' => '1']])->all();
        $company->features()->sync($featureSync);

        Cache::forget('home.payload.en');
        Cache::forget('home.payload.ru');

        return $company;
    }

    private function linesToArray(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text) ?: [])));
    }
}
