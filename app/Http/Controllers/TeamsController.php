<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\Sport;
use App\Models\Game; // Add this: Import the Game model (adjust namespace if needed)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Optional: If using auth for user-specific logic

class TeamsController extends Controller
{
    // ✅ Show all teams with wins & losses included
    public function index()
    {
        $teams = Team::with(['tournament', 'sport'])
            ->withCount('players')
            ->orderBy('team_name')
            ->get()
            ->map(function ($team) {
                // 🧠 Use the getRecord() method from Team model
                $record = $team->getRecord();
                $team->wins = $record['wins'] ?? 0;
                $team->losses = $record['losses'] ?? 0;
                return $team;
            });

        $tournaments = Tournament::orderBy('name')->get();
        $sports = Sport::all();

        // 🔧 NEW: Fetch $game variable (customize this query based on your app logic)
        // Assuming $game is context-specific, e.g., the latest active game, or tied to the user/team.
        // If it's per-team or user-specific, adjust accordingly. For now, fetching the first active game as example.
        // Replace with your actual logic (e.g., Game::where('status', 'active')->first() or based on auth user).
        $game = Game::first(); // Or: Game::find(session('current_game_id')); 
        // If tied to user: $game = Auth::check() && !session('is_guest') ? Game::where('user_id', Auth::id())->first() : null;

        // If no game found, set to null to avoid issues in the view
        if (!$game) {
            $game = null;
        }

        return view('teams', compact('teams', 'tournaments', 'sports', 'game'));
    }

    // Store new team
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name'     => 'required|string|max:255|unique:teams,team_name',
            'coach_name'    => 'nullable|string|max:255',
            'contact'       => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
            'sport_id'      => 'required|exists:sports,sports_id',
            'tournament_id' => 'nullable|exists:tournaments,id',
        ], [
            'team_name.unique' => 'This team name is already registered.',
        ]);

        Team::create($validated);

        // ✨ Enhanced success message with emoji
        return redirect()->route('teams.index')
            ->with('success', '🎉 Team has been successfully added!');
    }

    // Show single team
    public function show($id)
    {
        $team = Team::with(['players', 'sport'])->findOrFail($id);

        // 🔧 OPTIONAL: If show() also uses teams.blade or needs $game, add similar logic here
        // $game = Game::first(); // etc.
        // return view('team_show', compact('team', 'game'));

        return view('team_show', compact('team'));
    }

    // Update team
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'team_name'     => 'required|string|max:255|unique:teams,team_name,' . $team->id,
            'coach_name'    => 'nullable|string|max:255',
            'contact'       => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
            'sport_id'      => 'required|exists:sports,sports_id',
            'tournament_id' => 'nullable|exists:tournaments,id',
        ]);

        $team->update($validated);

        // ✨ Enhanced success message with emoji
        return redirect()->route('teams.index')
            ->with('success', '✅ Team has been successfully updated!');
    }

    // Delete team
    public function destroy(Team $team)
    {
        $teamName = $team->team_name; // Store name before deletion
        $team->delete();
        
        // ✨ Enhanced success message with emoji
        return redirect()->route('teams.index')
            ->with('success', '🗑️ ' . $teamName . ' has been successfully deleted!');
    }
}