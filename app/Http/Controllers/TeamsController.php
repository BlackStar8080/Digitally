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

        $game = session('is_guest') ? null : Game::first();

        return view('teams', compact('teams', 'tournaments', 'sports', 'game'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'max:255',
                Rule::unique('teams')->where(function ($query) use ($request) {
                    return $query->where('sport_id', $request->sport_id);
                }),
            ],
            'coach_name' => [
                'nullable',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',
                Rule::unique('teams', 'coach_name'),
            ],
            'contact'       => ['nullable', 'digits:11'],
            'address'       => ['nullable', 'regex:/^[a-zA-Z0-9\s]+$/', 'max:255'],
            'sport_id'      => 'required|exists:sports,sports_id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'logo'          => 'nullable|image|max:2048',
        ], [
            'team_name.regex' => 'Team name can only contain letters, numbers, and spaces.',
            'coach_name.regex' => 'Coach name can only contain letters and spaces.',
            'address.regex' => 'Location can only contain letters, numbers, and spaces.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',
            'team_name.unique' => 'A team with this name already exists in the selected sport.',
            'coach_name.unique' => 'This coach is already assigned to another team.',
        ]);

        // SANITIZE BEFORE SAVE
        $validated['team_name'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $validated['team_name']);
        $validated['coach_name'] = preg_replace('/[^a-zA-Z\s]/', '', $validated['coach_name'] ?? '');
        $validated['address'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $validated['address'] ?? '');
        $validated['contact'] = preg_replace('/[^0-9]/', '', $validated['contact'] ?? '');

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
                'regex:/^[a-zA-Z0-9\s]+$/',
                'max:255',
                Rule::unique('teams')->where(function ($query) use ($request, $team) {
                    return $query->where('sport_id', $request->sport_id)
                                 ->where('id', '!=', $team->id);
                }),
            ],
            'coach_name' => [
                'nullable',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',
                Rule::unique('teams', 'coach_name')->ignore($team->id),
            ],
            'contact'       => ['nullable', 'digits:11'],
            'address'       => ['nullable', 'regex:/^[a-zA-Z0-9\s]+$/', 'max:255'],
            'sport_id'      => 'required|exists:sports,sports_id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'logo'          => 'nullable|image|max:2048',
        ], [
            'team_name.regex' => 'Team name can only contain letters, numbers, and spaces.',
            'coach_name.regex' => 'Coach name can only contain letters and spaces.',
            'address.regex' => 'Location can only contain letters, numbers, and spaces.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',
            'team_name.unique' => 'A team with this name already exists in the selected sport.',
            'coach_name.unique' => 'This coach is already assigned to another team.',
        ]);

        // SANITIZE BEFORE UPDATE
        $validated['team_name'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $validated['team_name']);
        $validated['coach_name'] = preg_replace('/[^a-zA-Z\s]/', '', $validated['coach_name'] ?? '');
        $validated['address'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $validated['address'] ?? '');
        $validated['contact'] = preg_replace('/[^0-9]/', '', $validated['contact'] ?? '');

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
