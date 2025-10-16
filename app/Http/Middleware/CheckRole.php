<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('user.landing')->with('error', 'Please login to access this page.');
        }

        // Check if user has one of the required roles
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        return $next($request);
    }
}