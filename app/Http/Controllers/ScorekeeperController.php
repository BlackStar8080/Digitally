<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ScorekeeperController extends Controller
{
    /**
     * Display a listing of scorekeepers
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get scorekeepers with pagination
        $scorekeepers = $query->latest()->paginate(10);

        // Statistics
        $totalScorekeepers = User::count();
        $recentScorekeepers = User::where('created_at', '>=', now()->subDays(30))->count();
        
        return view('scorekeepers.index', compact('scorekeepers', 'totalScorekeepers', 'recentScorekeepers'));
    }

    /**
     * Store a newly created scorekeeper
     */
    public function store(Request $request)
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('scorekeepers.index')
            ->with('success', 'Scorekeeper registered successfully!');
    }

    /**
     * Update the specified scorekeeper
     */
    public function update(Request $request, User $scorekeeper)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9 ]+$/',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($scorekeeper->id),
            ],
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.regex' => 'The name must not contain special characters.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $scorekeeper->update($data);

        return redirect()->route('scorekeepers.index')
            ->with('success', 'Scorekeeper updated successfully!');
    }

    /**
     * Remove the specified scorekeeper
     */
    public function destroy(User $scorekeeper)
    {
        // Check if scorekeeper has submitted any tallysheets
        $hasTallysheets = $scorekeeper->tallysheets()->exists() || 
                         $scorekeeper->volleyballTallysheets()->exists();

        if ($hasTallysheets) {
            return redirect()->route('scorekeepers.index')
                ->with('error', 'Cannot delete scorekeeper who has submitted tallysheets. Consider deactivating instead.');
        }

        $scorekeeper->delete();

        return redirect()->route('scorekeepers.index')
            ->with('success', 'Scorekeeper deleted successfully!');
    }
}