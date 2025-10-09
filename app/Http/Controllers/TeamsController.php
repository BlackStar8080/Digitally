<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamsController extends Controller
{
    // ✅ Show all teams with wins & losses included
    public function index()
    {
        $teams = Team::with('tournament')
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

        return view('teams', compact('teams', 'tournaments'));
    }

    // Store new team
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name'     => 'required|string|max:255|unique:teams,team_name',
            'coach_name'    => 'nullable|string|max:255',
            'contact'       => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
            'sport'         => 'required|string|max:100',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Add validation
        ], [
            'team_name.unique' => 'This team name is already registered.',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'The logo must not be greater than 2MB.',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('team_logos', 'public');
            $validated['logo'] = $logoPath;
        }

        Team::create($validated);

        // ✨ Enhanced success message with emoji
        return redirect()->route('teams.index')
            ->with('success', '🎉 Team has been successfully added!');
    }

    // Show single team
    public function show($id)
    {
        $team = Team::with(['players'])->findOrFail($id);

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
            'sport'         => 'required|string|max:100',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Add validation
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }
            
            $logoPath = $request->file('logo')->store('team_logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $team->update($validated);

        // ✨ Enhanced success message with emoji
        return redirect()->route('teams.index')
            ->with('success', '✅ Team has been successfully updated!');
    }

    // Delete team
    public function destroy(Team $team)
    {
        $teamName = $team->team_name; // Store name before deletion
        
        // Delete logo if exists
        if ($team->logo && Storage::disk('public')->exists($team->logo)) {
            Storage::disk('public')->delete($team->logo);
        }
        
        $team->delete();
        
        // ✨ Enhanced success message with emoji
        return redirect()->route('teams.index')
            ->with('success', '🗑️ ' . $teamName . ' has been successfully deleted!');
    }
}