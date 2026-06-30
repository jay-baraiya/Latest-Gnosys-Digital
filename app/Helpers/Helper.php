<?php

namespace App\Helpers;

use App\Models\UserRolePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Helper
{

    public static function measureQueryTime($callback)
    {
        DB::enableQueryLog();

        $startTime = microtime(true);

        $result = $callback();

        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000, 2);

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        dump([
            'total_time_ms' => $executionTime,
            'total_queries' => count($queries),
            'queries' => $queries
        ]);

        return $result;
    }

    public static function getPermissions($permission = 'role')
    {
        if (app()->bound('permissions')) {
            return app('permissions');
        }

        $user = Auth::user();


        if (!$user->is_user_permission) {
            $permissions = DB::table('roles')
                ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
                ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('user_roles.user_id', $user->id)
                ->where('permissions.status', 1)
                ->whereNull('permissions.deleted_at')
                ->distinct()
                ->pluck('permissions.slug')
                ->toArray();
        } else {
            $permissions = UserRolePermission::query()
                                ->with(['permission'])
                                ->where('user_id', $user->id)
                                ->where('role_id', $user?->role?->id)
                                ->get()
                                ->pluck('permission')
                                ->flatten()
                                ->pluck('slug')
                                ->toArray();
        }

        app()->instance('permissions', $permissions);

        return $permissions;
    }
}
