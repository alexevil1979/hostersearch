<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  string  ...$roles  Role values: user, partner, admin, super_admin
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403);
        }

        $allowed = collect($roles)
            ->map(fn (string $role) => UserRole::tryFrom($role))
            ->filter();

        if ($user->role === UserRole::SuperAdmin && $allowed->contains(UserRole::Admin)) {
            return $next($request);
        }

        if (! $allowed->contains($user->role)) {
            abort(403);
        }

        return $next($request);
    }
}
