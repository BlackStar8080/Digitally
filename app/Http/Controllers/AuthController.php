<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->with('form_type', 'login'); // ✅ Added for modal
    }


    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9 ]+$/', // ✅ No special characters
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.regex' => 'The name must not contain special characters.',
        ]);

        // Check if validation fails manually to add form_type
        $validator = \Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9 ]+$/',
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('form_type', 'register'); // ✅ Added for modal
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing'); 
    }

    public function guestLogin(Request $request)
{
    // Set a session flag for guest user
    session(['is_guest' => true]);
    session(['guest_name' => 'Guest User']);
    
    // Redirect to a guest dashboard or main system page
    return redirect()->route('dashboard')->with('success', 'Browsing as Guest');
}
}