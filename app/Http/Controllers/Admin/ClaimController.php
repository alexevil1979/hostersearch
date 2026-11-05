<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClaimStatus;
use App\Enums\CompanyStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Claim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function index(): View
    {
        $claims = Claim::query()->with(['company', 'user', 'reviewer'])->latest()->paginate(25);

        return view('admin.claims.index', compact('claims'));
    }

    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load(['company', 'user']);

        $claim->update([
            'status' => ClaimStatus::Approved,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'admin_note' => $request->input('admin_note'),
        ]);

        if ($claim->company && $claim->user) {
            $claim->company->update([
                'status' => CompanyStatus::Claimed,
                'claimed_by_user_id' => $claim->user_id,
                'claimed_at' => now(),
            ]);

            if ($claim->user->role === UserRole::User) {
                $claim->user->update(['role' => UserRole::Partner]);
            }
        }

        ActivityLog::record('claim.approved', $claim);

        return back()->with('status', 'Claim approved. Partner linked.');
    }

    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        $claim->update([
            'status' => ClaimStatus::Rejected,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'admin_note' => $request->input('admin_note'),
        ]);
        ActivityLog::record('claim.rejected', $claim);

        return back()->with('status', 'Claim rejected.');
    }
}
