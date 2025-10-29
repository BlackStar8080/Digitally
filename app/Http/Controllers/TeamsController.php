<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\Sport;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeamsController extends Controller
{
    public function index()
    {
        $teams = Team::with(['tournament', 'sport'])
            ->withCount('players')
            ->orderBy('team_name')
            ->get()
            ->map(function ($team) {
                $record = $team->getRecord();
                $team->wins = $record['wins'] ?? 0;
                $team->losses = $record['losses'] ?? 0;
                return $team;
            });

        $tournaments = Tournament::orderBy('name')->get();
        $sports = Sport::all();

        // Customize this based on how games are selected
        $game = session('is_guest') ? null : Game::first();
        // Alternatives:
        // $game = Auth::check() ? Game::where('user_id', Auth::id())->first() : null;
        // $game = Game::where('status', 'active')->first();
        // $game = Game::find(session('current_game_id'));

        return view('teams', compact('teams', 'tournaments', 'sports', 'game'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'team_name' => [
            'required',
            'string',
            'max:255',
            // Team name unique only within the same sport
            Rule::unique('teams')->where(function ($query) use ($request) {
                return $query->where('sport_id', $request->sport_id);
            }),
        ],
        'coach_name' => [
            'nullable',
            'string',
            'max:255',
            // Coach name must be unique across all teams
            Rule::unique('teams', 'coach_name'),
        ],
        'contact'       => 'nullable|string|max:255',
        'address'       => 'nullable|string|max:255',
        'sport_id'      => 'required|exists:sports,sports_id',
        'tournament_id' => 'nullable|exists:tournaments,id',
        'logo'          => 'nullable|image|max:2048',
    ], [
        'team_name.unique' => 'A team with this name already exists in the selected sport.',
        'coach_name.unique' => 'This coach is already assigned to another team.',
    ]);

    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('team_logos', 'public');
        $validated['logo'] = $path;
    }

    Team::create($validated);

    return redirect()->route('teams.index')
        ->with('success', '🎉 Team has been successfully added!');
}


    public function show($id)
    {
        $team = Team::with(['players', 'sport'])->findOrFail($id);
        return view('team_show', compact('team'));
    }

    public function update(Request $request, Team $team)
{
    $validated = $request->validate([
        'team_name' => [
            'required',
            'string',
            'max:255',
            // Still unique only in the same sport, excluding this team
            Rule::unique('teams')->where(function ($query) use ($request, $team) {
                return $query->where('sport_id', $request->sport_id)
                             ->where('id', '!=', $team->id);
            }),
        ],
        'coach_name' => [
            'nullable',
            'string',
            'max:255',
            // Coach name unique globally, but ignore the current team
            Rule::unique('teams', 'coach_name')->ignore($team->id),
        ],
        'contact'       => 'nullable|string|max:255',
        'address'       => 'nullable|string|max:255',
        'sport_id'      => 'required|exists:sports,sports_id',
        'tournament_id' => 'nullable|exists:tournaments,id',
        'logo'          => 'nullable|image|max:2048',
    ], [
        'team_name.unique' => 'A team with this name already exists in the selected sport.',
        'coach_name.unique' => 'This coach is already assigned to another team.',
    ]);

    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('team_logos', 'public');
        $validated['logo'] = $path;
    }

    $team->update($validated);

    return redirect()->route('teams.index')
        ->with('success', '✅ Team has been successfully updated!');
}


    public function destroy(Team $team)
    {
        $teamName = $team->team_name;
        $team->delete();
        
        return redirect()->route('teams.index')
            ->with('success', '🗑️ ' . $teamName . ' has been successfully deleted!');
    }
}