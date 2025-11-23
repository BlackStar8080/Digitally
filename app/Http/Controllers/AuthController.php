<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('🔵 Login attempt started', [
            'email' => $request->email,
            'session_id_before' => session()->getId(),
            'has_pending_join_before' => session()->has('pending_game_join'),
            'pending_join_before' => session('pending_game_join'),
        ]);
        
        if (Auth::attempt($request->only('email', 'password'))) {
            
            // ✅ CRITICAL: Save pending join BEFORE regenerating session
            $pendingJoin = session('pending_game_join');
            
            \Log::info('✅ Login successful', [
                'user_id' => auth()->id(),
                'pending_join_captured' => $pendingJoin,
            ]);
            
            // Regenerate session for security
            $request->session()->regenerate();
            
            \Log::info('🔄 Session regenerated', [
                'new_session_id' => session()->getId(),
                'pending_join_after_regenerate' => session('pending_game_join'),
            ]);
            
            // ✅ Restore pending join data if it was there
            if ($pendingJoin) {
                session(['pending_game_join' => $pendingJoin]);
                session()->save(); // Force save
                
                \Log::info('✅ Restored pending join after regeneration', [
                    'restored_data' => $pendingJoin,
                    'session_now_has_key' => session()->has('pending_game_join'),
                ]);
            }
            
            // Clear guest session
            session()->forget('is_guest');
            session()->forget('guest_name');
            
            // Check if we have pending game join
            if (session()->has('pending_game_join')) {
                $joinData = session('pending_game_join');
                
                \Log::info('🎯 Redirecting to games.join', [
                    'user_id' => auth()->id(),
                    'join_data' => $joinData,
                ]);
                
                return redirect()->route('games.join', [
                    'game' => $joinData['game_id'],
                    'token' => $joinData['token']
                ]);
            }
            
            \Log::info('✅ Normal login - redirecting to dashboard', [
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('dashboard')->with('success', 'Logged in successfully');
        }

        \Log::warning('❌ Login failed - invalid credentials');

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput()->with('form_type', 'login');
    }

    // ✅ REMOVED: Registration code validation
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
        ], [
            'name.regex' => 'The name must not contain special characters.',
        ]);

        // ✅ SAVE PENDING JOIN DATA BEFORE CREATING USER
        $pendingJoin = session('pending_game_join');
        
        // ✅ DEBUG LOG
        if ($pendingJoin) {
            \Log::info('🔵 Pending game join data found BEFORE registration', [
                'pending_join' => $pendingJoin
            ]);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        
        // ✅ RESTORE PENDING JOIN DATA AFTER LOGIN
        if ($pendingJoin) {
            session(['pending_game_join' => $pendingJoin]);
            
            \Log::info('✅ Restored pending join data AFTER registration', [
                'user_id' => auth()->id(),
                'pending_join' => $pendingJoin
            ]);
        }
        
        // Clear guest session
        session()->forget('is_guest');
        session()->forget('guest_name');

        // ✅ CHECK IF USER WAS TRYING TO JOIN A GAME
        if (session()->has('pending_game_join')) {
            $joinData = session('pending_game_join');
            
            \Log::info('🎯 New user registered with pending game join', [
                'user_id' => auth()->id(),
                'game_id' => $joinData['game_id'],
                'token' => $joinData['token']
            ]);
            
            return redirect()->route('games.join', [
                'game' => $joinData['game_id'],
                'token' => $joinData['token']
            ]);
        }

        \Log::info('✅ Normal registration - no pending game join', [
            'user_id' => auth()->id()
        ]);

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