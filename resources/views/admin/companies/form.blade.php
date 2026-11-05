@extends('layouts.admin')
@section('title', $company->exists ? 'Edit company' : 'New company')
@section('heading', $company->exists ? 'Edit: '.$company->name : 'New company')

@section('content')
<form method="post" action="{{ $company->exists ? route('admin.companies.update', $company) : route('admin.companies.store') }}" class="space-y-6">
    @csrf
    @if ($company->exists) @method('PUT') @endif

    <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 lg:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name', $company->name) }}" required class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Slug</label>
            <input name="slug" value="{{ old('slug', $company->slug) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Website</label>
            <input name="website" value="{{ old('website', $company->website) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Affiliate URL</label>
            <input name="affiliate_url" value="{{ old('affiliate_url', $company->affiliate_url) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Founded</label>
            <input type="number" name="founded" value="{{ old('founded', $company->founded) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Headquarters</label>
            <input name="headquarters" value="{{ old('headquarters', $company->headquarters) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Score</label>
            <input type="number" step="0.1" name="overall_score" value="{{ old('overall_score', $company->overall_score) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Price from</label>
            <input type="number" step="0.01" name="price_from" value="{{ old('price_from', $company->price_from) }}" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200">
                @foreach (['pending','approved','claimed','rejected','suspended'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $company->status?->value)===$status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-4">
            <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $company->is_featured))> Featured</label>
            <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="is_recommended" value="1" @checked(old('is_recommended', $company->is_recommended))> Recommended</label>
        </div>
        <div class="lg:col-span-2">
            <label class="mb-1 block text-sm font-medium">Short description</label>
            <textarea name="short_description" rows="2" class="w-full rounded-xl border-slate-200">{{ old('short_description', $company->short_description) }}</textarea>
        </div>
        <div class="lg:col-span-2">
            <label class="mb-1 block text-sm font-medium">Expert review</label>
            <textarea name="expert_review" rows="6" class="w-full rounded-xl border-slate-200">{{ old('expert_review', $company->expert_review) }}</textarea>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Pros (one per line)</label>
            <textarea name="pros" rows="4" class="w-full rounded-xl border-slate-200">{{ old('pros', implode("\n", $company->pros ?? [])) }}</textarea>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Cons (one per line)</label>
            <textarea name="cons" rows="4" class="w-full rounded-xl border-slate-200">{{ old('cons', implode("\n", $company->cons ?? [])) }}</textarea>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Categories</label>
            <div class="max-h-40 space-y-1 overflow-y-auto rounded-xl border border-slate-200 p-3 text-sm">
                @foreach ($categories as $category)
                    <label class="flex items-center gap-2"><input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categories', $selectedCategories), true))> {{ $category->name }}</label>
                @endforeach
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Types</label>
            <div class="max-h-40 space-y-1 overflow-y-auto rounded-xl border border-slate-200 p-3 text-sm">
                @foreach ($types as $type)
                    <label class="flex items-center gap-2"><input type="checkbox" name="types[]" value="{{ $type->id }}" @checked(in_array($type->id, old('types', $selectedTypes), true))> {{ $type->name }}</label>
                @endforeach
            </div>
        </div>
        <div class="lg:col-span-2">
            <label class="mb-1 block text-sm font-medium">Features</label>
            <div class="grid max-h-48 grid-cols-2 gap-1 overflow-y-auto rounded-xl border border-slate-200 p-3 text-sm md:grid-cols-3">
                @foreach ($features as $feature)
                    <label class="flex items-center gap-2"><input type="checkbox" name="features[]" value="{{ $feature->id }}" @checked(in_array($feature->id, old('features', $selectedFeatures), true))> {{ $feature->name }}</label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white">Save</button>
        <a href="{{ route('admin.companies.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm">Cancel</a>
        @if ($company->exists)
            <a href="{{ route('companies.show', $company) }}" target="_blank" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm">View public</a>
        @endif
    </div>
</form>

@if ($company->exists)
    <section class="mt-10 rounded-2xl border border-slate-200 bg-white p-5">
        <h2 class="mb-4 text-lg font-semibold">Plans</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-2 text-left">Name</th><th class="px-3 py-2">Price</th><th class="px-3 py-2">Storage</th><th class="px-3 py-2"></th></tr></thead>
                <tbody>
                @foreach ($company->plans as $plan)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $plan->name }}</td>
                        <td class="px-3 py-2 text-center">{{ $plan->price }}</td>
                        <td class="px-3 py-2 text-center">{{ $plan->space_gb ?? '∞' }}</td>
                        <td class="px-3 py-2 text-right">
                            <form method="post" action="{{ route('admin.companies.plans.destroy', [$company, $plan]) }}" onsubmit="return confirm('Delete plan?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <form method="post" action="{{ route('admin.companies.plans.store', $company) }}" class="mt-6 grid gap-3 border-t border-slate-100 pt-6 md:grid-cols-4">
            @csrf
            <input name="name" placeholder="Plan name" required class="rounded-xl border-slate-200">
            <input type="number" step="0.01" name="price" placeholder="Price" required class="rounded-xl border-slate-200">
            <input type="number" name="space_gb" placeholder="GB" class="rounded-xl border-slate-200">
            <input name="panel" placeholder="Panel" class="rounded-xl border-slate-200">
            <button class="rounded-xl bg-brand-800 px-4 py-2 text-sm font-semibold text-white md:col-span-4 md:w-fit">Add plan</button>
        </form>
    </section>

    <form method="post" action="{{ route('admin.companies.destroy', $company) }}" class="mt-6" onsubmit="return confirm('Delete company?')">
        @csrf @method('DELETE')
        <button class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700">Delete company</button>
    </form>
@endif
@endsection
