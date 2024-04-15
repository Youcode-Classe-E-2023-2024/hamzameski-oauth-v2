<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\UserHasRole;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $roleId = Role::where('name', $role)->value('id');

        if (!$roleId) {
            return response()->json(['error' => 'Role not found.'], 403);
        }

        $userHasRole = UserHasRole::where('user_id', auth()->id())
            ->where('role_id', $roleId)
            ->exists();

        if (!$userHasRole) {
            return response()->json(['error' => 'You do not have the required role by hamza :)'], 403);
        }

        return $next($request);
    }
}
