<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlayersController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with('team');

        // 🔍 Apply filters
        if ($request->filled('team_id') && $request->team_id !== 'all') {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('sport') && $request->sport !== 'all') {
            $query->where('sport', $request->sport);
        }

        if ($request->filled('position') && $request->position !== 'all') {
            $query->where('position', $request->position);
        }

        // ✅ Real database search (not just client-side)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('sport', 'like', "%{$search}%")
                  ->orWhereHas('team', fn($team) => $team->where('team_name', 'like', "%{$search}%"));
            });
        }

        // ✅ Paginate and keep query params
        $players = $query->orderBy('name')->paginate(15)->withQueryString();

        $teams = Team::all();
        $tournaments = Tournament::all();
        $positions = Player::select('position')->whereNotNull('position')->distinct()->pluck('position');
        $sports = Player::select('sport')->whereNotNull('sport')->distinct()->pluck('sport');

        return view('players', compact('players', 'teams', 'tournaments', 'positions', 'sports'));
    }

    // Store new player
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('players')->where(fn($query) => $query->where('team_id', $request->team_id)),
            ],
            'team_id'   => 'required|exists:teams,id',
            'sport'     => 'required|string|max:100',
            'number'    => 'nullable|integer',
            'position'  => 'nullable|string|max:50',
            'birthday'  => 'required|date',
        ], [
            'name.unique' => 'This player already exists in the selected team.',
        ]);

        // Compute age from birthday
        $birthday = $request->input('birthday');
        $age = null;
        if ($birthday) {
            $age = \Carbon\Carbon::parse($birthday)->age;
        }

        $validated['age'] = $age;

        Player::create($validated);

        return redirect()->route('players.index')->with('success', 'Player added successfully!');
    }

    public function edit(Player $player)
    {
        return redirect()->route('players.index');
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('players')->where(fn($query) => 
                    $query->where('team_id', $request->team_id)->where('id', '!=', $player->id)
                ),
            ],
            'team_id'   => 'required|exists:teams,id',
            'sport'     => 'required|string|max:100',
            'number'    => 'nullable|integer',
            'position'  => 'nullable|string|max:50',
            'birthday'  => 'required|date',
        ], [
            'name.unique' => 'This player already exists in the selected team.',
        ]);

        // Compute age from birthday
        $birthday = $request->input('birthday');
        $age = null;
        if ($birthday) {
            $age = \Carbon\Carbon::parse($birthday)->age;
        }

        $validated['age'] = $age;

        $player->update($validated);

        return redirect()->route('players.index')->with('success', 'Player updated successfully!');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Player deleted successfully!');
    }
}
