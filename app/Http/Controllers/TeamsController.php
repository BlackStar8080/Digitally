<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\Sport;
use Illuminate\Http\Request;

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

        return view('teams', compact('teams', 'tournaments', 'sports'));
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