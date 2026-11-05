@extends('layouts.admin')
@section('title', 'Companies')
@section('heading', 'Companies')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <form method="get" class="flex flex-wrap gap-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search..." class="rounded-xl border-slate-200 text-sm">
        <select name="status" class="rounded-xl border-slate-200 text-sm">
            <option value="">All statuses</option>
            @foreach (['pending','approved','claimed','rejected','suspended'] as $status)
                <option value="{{ $status }}" @selected(request('status')===$status)>{{ $status }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-brand-800 px-4 py-2 text-sm font-semibold text-white">Filter</button>
    </form>
    <a href="{{ route('admin.companies.create') }}" class="rounded-xl bg-brand-700 px-4 py-2 text-sm font-semibold text-white">Add company</a>
</div>

<form method="post" action="{{ route('admin.companies.bulk') }}">
    @csrf
    <div class="mb-3 flex flex-wrap items-center gap-2">
        <select name="action" required class="rounded-xl border-slate-200 text-sm">
            <option value="">Bulk action…</option>
            <option value="approve">Approve</option>
            <option value="feature">Feature</option>
            <option value="unfeature">Unfeature</option>
            <option value="delete">Delete</option>
        </select>
        <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">Apply</button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-3 py-3"><input type="checkbox" onclick="document.querySelectorAll('.cid').forEach(c=>c.checked=this.checked)"></th>
                    <th class="px-3 py-3">Name</th>
                    <th class="px-3 py-3">Score</th>
                    <th class="px-3 py-3">Status</th>
                    <th class="px-3 py-3">Plans</th>
                    <th class="px-3 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                    <tr class="border-t border-slate-100">
                        <td class="px-3 py-3"><input class="cid" type="checkbox" name="ids[]" value="{{ $company->id }}"></td>
                        <td class="px-3 py-3 font-medium">{{ $company->name }}</td>
                        <td class="px-3 py-3">{{ number_format($company->overall_score, 1) }}</td>
                        <td class="px-3 py-3">{{ $company->status->value }}</td>
                        <td class="px-3 py-3">{{ $company->plans_count }}</td>
                        <td class="px-3 py-3 text-right">
                            <a href="{{ route('admin.companies.edit', $company) }}" class="text-brand-700 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>
<div class="mt-4">{{ $companies->links() }}</div>
@endsection
