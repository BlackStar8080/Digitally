<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'Scorekeeper_email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('Scorekeeper_email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'Scorekeeper_email' => 'The provided credentials do not match our records.',
        ])->with('form_type', 'login');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'Scorekeeper_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9 ]+$/',
            ],
            'Scorekeeper_email' => 'required|email|unique:users,Scorekeeper_email',
            'password' => 'required|min:8|confirmed',
        ], [
            'Scorekeeper_name.regex' => 'The name must not contain special characters.',
        ]);

        $user = User::create([
            'Scorekeeper_name' => $request->Scorekeeper_name,
            'Scorekeeper_email' => $request->Scorekeeper_email,
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
}
