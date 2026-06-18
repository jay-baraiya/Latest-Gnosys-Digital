<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = ''): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (empty(Auth::user()->role?->id)) {
            abort(403, 'Unauthorized action.');
        }

        if (Gate::denies($permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
