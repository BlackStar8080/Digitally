<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\Team;

class TournamentController extends Controller
{
    // Show all tournaments
    public function index()
    {
        // Sort tournaments by start date ascending
        $tournaments = Tournament::orderBy('start_date', 'asc')->get();

        return view('tournaments', compact('tournaments'));
    }

    // Store new tournament
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255|unique:tournaments,name',
            'division'     => 'required|string|max:255',
            'sport'        => 'required|string|max:100',
            'bracket_type' => 'required|string|max:100',
            'start_date'   => 'nullable|date',
        ], [
            'name.unique' => 'This tournament name is already registered.',
        ]);

        Tournament::create($validated);

        // ✨ Enhanced success message with emoji
        return redirect()->route('tournaments.index')
            ->with('success', '🎉 Tournament has been successfully created!');
    }

    // View a single tournament
    public function show($id)
    {
        $tournament = Tournament::findOrFail($id);

        return view('tournaments.show', compact('tournament'));
    }

    // Update tournament
    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255|unique:tournaments,name,' . $tournament->id,
            'division'     => 'required|string|max:255',
            'sport'        => 'required|string|max:100',
            'bracket_type' => 'required|string|max:100',
            'start_date'   => 'nullable|date',
        ], [
            'name.unique' => 'This tournament name is already registered.',
        ]);

        $tournament->update($validated);

        // ✨ Enhanced success message with emoji
        return redirect()->route('tournaments.index')
            ->with('success', '✅ Tournament has been successfully updated!');
    }

    // Delete tournament
    public function destroy(Tournament $tournament)
    {
        $tournamentName = $tournament->name; // Store name before deletion
        $tournament->delete();
        
        // ✨ Enhanced success message with emoji
        return redirect()->route('tournaments.index')
            ->with('success', '🗑️ ' . $tournamentName . ' has been successfully deleted!');
    }

    /**
     * Assign multiple teams to a tournament
     */
    public function assignTeams(Request $request, $tournamentId)
    {
        $validated = $request->validate([
            'team_ids' => 'required|array|min:1',
            'team_ids.*' => 'required|exists:teams,id',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);
        $successCount = 0;
        $errors = [];

        foreach ($validated['team_ids'] as $teamId) {
            $team = Team::findOrFail($teamId);

            // Check if team sport matches tournament sport (case-insensitive)
            if (strtolower($team->sport) !== strtolower($tournament->sport)) {
                $errors[] = "Team '{$team->team_name}' sport does not match tournament sport.";
                continue;
            }

            // Check if team is already assigned to another tournament
            if ($team->tournament_id && $team->tournament_id !== $tournament->id) {
                $errors[] = "Team '{$team->team_name}' is already assigned to another tournament.";
                continue;
            }

            // Skip if already assigned to this tournament
            if ($team->tournament_id === $tournament->id) {
                continue;
            }

            // Assign team to tournament
            $team->update(['tournament_id' => $tournament->id]);
            $successCount++;
        }

        // Prepare response message
        $message = "";
        if ($successCount > 0) {
            $message = "{$successCount} team" . ($successCount > 1 ? 's' : '') . " added successfully!";
        }

        if (!empty($errors)) {
            $errorMessage = implode(' ', $errors);
            if ($successCount > 0) {
                $message .= " However, some teams couldn't be added: " . $errorMessage;
            } else {
                return back()->with('error', $errorMessage);
            }
        }

        return back()->with('success', $message);
    }
}