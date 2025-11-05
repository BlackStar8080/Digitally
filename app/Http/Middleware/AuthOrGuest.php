<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthOrGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow if user is authenticated OR is a guest
        if (auth()->check() || session()->has('is_guest')) {
            return $next($request);
        }

        // Redirect to landing page if neither authenticated nor guest
        return redirect()->route('landing')->with('error', 'Please log in or continue as guest.');
    }
}