<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermissionService
{
    public function check(string $action, string $table): bool
    {
        // 1. Main User (Full Access)
        if (Auth::guard('web')->check()) {
            return true;
        }

        // 2. Subuser (Role-based access)
        if (Auth::guard('subusers')->check()) {

            $subUser = Auth::guard('subusers')->user();

            if (!$subUser->role) {
                return false;
            }

            return $subUser->role->permissions()
                ->where('slug', $action)
                ->wherePivot('table_name', $table)
                ->exists();
        }

        // 3. Not logged in
        return false;
    }
}
