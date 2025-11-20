<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RegistrationCode;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // ✅ SAVE PENDING JOIN DATA BEFORE SESSION REGENERATION
    $pendingJoin = session('pending_game_join');
    
    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        
        // ✅ RESTORE PENDING JOIN DATA AFTER REGENERATION
        if ($pendingJoin) {
            session(['pending_game_join' => $pendingJoin]);
        }
        
        // Clear guest session
        session()->forget('is_guest');
        session()->forget('guest_name');
        
        // ✅ CHECK IF USER WAS TRYING TO JOIN A GAME
        if (session()->has('pending_game_join')) {
            $joinData = session('pending_game_join');
            
            // Clear the session
            session()->forget('pending_game_join');
            
            \Log::info('✅ Redirecting user to join game after login', [
                'user_id' => auth()->id(),
                'game_id' => $joinData['game_id']
            ]);
            
            // Redirect back to join route with token
            return redirect()->route('games.join', [
                'game' => $joinData['game_id'],
                'token' => $joinData['token']
            ])->with('success', 'Joining game as Stat-keeper...');
        }
        
        return redirect()->route('dashboard')->with('success', 'Logged in successfully');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->with('form_type', 'login');
}

    public function register(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-z0-9 ]+$/',
        ],
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'registration_code' => 'required|string',
    ], [
        'name.regex' => 'The name must not contain special characters.',
        'registration_code.required' => 'Registration code is required.',
    ]);

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

    // ✅ SAVE PENDING JOIN DATA BEFORE CREATING USER
    $pendingJoin = session('pending_game_join');

    // Create user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    $registrationCode->incrementUsage();

    Auth::login($user);
    
    // ✅ RESTORE PENDING JOIN DATA AFTER LOGIN
    if ($pendingJoin) {
        session(['pending_game_join' => $pendingJoin]);
    }
    
    // Clear guest session
    session()->forget('is_guest');
    session()->forget('guest_name');

    // ✅ CHECK IF USER WAS TRYING TO JOIN A GAME
    if (session()->has('pending_game_join')) {
        $joinData = session('pending_game_join');
        
        session()->forget('pending_game_join');
        
        \Log::info('✅ Redirecting new user to join game after registration', [
            'user_id' => auth()->id(),
            'game_id' => $joinData['game_id']
        ]);
        
        return redirect()->route('games.join', [
            'game' => $joinData['game_id'],
            'token' => $joinData['token']
        ])->with('success', 'Account created! Joining game as Stat-keeper...');
    }

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