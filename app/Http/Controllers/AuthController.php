<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RegistrationCode; // ✅ Add this

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            // ✅ IMPORTANT: Clear guest session when real user logs in
            session()->forget('is_guest');
            session()->forget('guest_name');
            
            return redirect()->route('dashboard')->with('success', 'Logged in successfully');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->with('form_type', 'login');
    }

    public function register(Request $request)
    {
        // ✅ UPDATED: Add registration code validation
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9 ]+$/',
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'registration_code' => 'required|string', // ✅ Add this
        ], [
            'name.regex' => 'The name must not contain special characters.',
            'registration_code.required' => 'Registration code is required.', // ✅ Add this
        ]);

        // ✅ NEW: Validate registration code
        $registrationCode = RegistrationCode::where('code', $request->registration_code)->first();

        if (!$registrationCode) {
            return back()->withErrors([
                'registration_code' => 'Invalid registration code.'
            ])->with('form_type', 'register')->withInput();
        }

        if (!$registrationCode->isValid()) {
            $reason = '';
            if (!$registrationCode->is_active) {
                $reason = 'This registration code has been disabled.';
            } elseif ($registrationCode->expires_at && now()->greaterThan($registrationCode->expires_at)) {
                $reason = 'This registration code has expired.';
            } elseif ($registrationCode->max_uses && $registrationCode->used_count >= $registrationCode->max_uses) {
                $reason = 'This registration code has reached its maximum usage limit.';
            }

            return back()->withErrors([
                'registration_code' => $reason ?: 'Invalid registration code.'
            ])->with('form_type', 'register')->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // ✅ NEW: Increment registration code usage
        $registrationCode->incrementUsage();

        Auth::login($user);
        
        // ✅ IMPORTANT: Clear guest session when user registers
        session()->forget('is_guest');
        session()->forget('guest_name');

        return redirect()->route('dashboard')->with('success', 'Account created and logged in successfully');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        session()->forget('is_guest');
        session()->forget('guest_name');

        return redirect()->route('landing')->with('success', 'You have been logged out successfully.');
    }

    public function guestLogin(Request $request)
    {
        session(['is_guest' => true]);
        session(['guest_name' => 'Guest User']);
        
        return redirect()->route('dashboard')->with('success', 'Browsing as Guest');
    }
}