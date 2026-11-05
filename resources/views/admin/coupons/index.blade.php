@extends('layouts.admin')
@section('title', 'Coupons')
@section('heading', 'Coupons')

@section('content')
<form method="post" action="{{ route('admin.coupons.store') }}" class="mb-6 grid gap-3 rounded-2xl border border-slate-200 bg-white p-5 md:grid-cols-3">
    @csrf
    <select name="company_id" required class="rounded-xl border-slate-200">
        <option value="">Company</option>
        @foreach ($companies as $company)
            <option value="{{ $company->id }}">{{ $company->name }}</option>
        @endforeach
    </select>
    <input name="code" required placeholder="CODE" class="rounded-xl border-slate-200">
    <input name="title" required placeholder="Title" class="rounded-xl border-slate-200">
    <input name="discount" placeholder="Discount label" class="rounded-xl border-slate-200">
    <input type="date" name="expires_at" class="rounded-xl border-slate-200">
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="exclusive" value="1"> Exclusive</label>
    <button class="rounded-xl bg-brand-700 px-4 py-2 text-sm font-semibold text-white md:col-span-3 md:w-fit">Add coupon</button>
</form>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
<table class="min-w-full text-sm">
    <thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-3 text-left">Company</th><th class="px-3 py-3">Code</th><th class="px-3 py-3">Title</th><th class="px-3 py-3">Clicks</th><th class="px-3 py-3"></th></tr></thead>
    <tbody>
    @foreach ($coupons as $coupon)
        <tr class="border-t">
            <td class="px-3 py-3">{{ $coupon->company?->name }}</td>
            <td class="px-3 py-3 font-mono">{{ $coupon->code }}</td>
            <td class="px-3 py-3">{{ $coupon->title }}</td>
            <td class="px-3 py-3 text-center">{{ $coupon->clicks }}</td>
            <td class="px-3 py-3 text-right">
                <form method="post" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                    <button class="text-rose-600">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-4">{{ $coupons->links() }}</div>
@endsection
