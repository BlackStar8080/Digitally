<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGuestAccess
{
    public function handle(Request $request, Closure $next)
    {
        // If user is a guest, redirect with error for restricted actions
        if (session('is_guest')) {
            return redirect()->back()->with('error', 'This action is not available for guest users. Please log in.');
        }
        
        return $next($request);
    }
}