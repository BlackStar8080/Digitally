<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGuestAccess
{
    public function handle(Request $request, Closure $next)
    {
        // If user is a guest, block the action
        if (session('is_guest')) {
            return redirect()->back()->with('error', 'This action is not available for guest users. Please register or log in.');
        }
        
        return $next($request);
    }
}