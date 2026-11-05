<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function create(Request $request): View
    {
        $companies = Company::query()->approved()->orderBy('name')->get(['id', 'name', 'slug']);
        $selected = $request->integer('company_id') ?: null;

        return view('public.claim', compact('companies', 'selected'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:60'],
            'company_email' => ['nullable', 'email', 'max:180'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        Claim::query()->create([
            ...$data,
            'user_id' => $request->user()->id,
            'status' => ClaimStatus::Pending,
        ]);

        return redirect()->route('claim.create')->with('status', __('Claim request submitted. We will verify ownership soon.'));
    }
}
