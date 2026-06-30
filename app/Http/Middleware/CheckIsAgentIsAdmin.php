<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAgentIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()?->role?->id == User::IS_BUYER) {
            return redirect()->route('home');
        }

        if (!Auth::user()->status) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
