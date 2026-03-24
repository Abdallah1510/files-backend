<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'غير مصرح به - يجب تسجيل الدخول',
                'status' => 401
            ], 401);
        }

        $user = Auth::user();
        
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'غير مصرح به - ليس لديك الصلاحية المطلوبة',
            'status' => 403
        ], 403);
    }
}