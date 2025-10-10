<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlayersController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with(['team', 'sport']);

        // 🔍 Apply filters
        if ($request->filled('team_id') && $request->team_id !== 'all') {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('sport_id') && $request->sport_id !== 'all') {
            $query->where('sport_id', $request->sport_id);
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
                  ->orWhereHas('team', fn($team) => $team->where('team_name', 'like', "%{$search}%"))
                  ->orWhereHas('sport', fn($sport) => $sport->where('sports_name', 'like', "%{$search}%"));
            });
        }

        // ✅ Paginate and keep query params
        $players = $query->orderBy('name')->paginate(15)->withQueryString();

        $teams = Team::all();
        $sports = Sport::all();
        $positions = Player::select('position')->whereNotNull('position')->distinct()->pluck('position');

        return view('players', compact('players', 'teams', 'sports', 'positions'));
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
            'sport_id'  => 'required|exists:sports,sports_id',
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

        // ✨ Enhanced success message with emoji
        return redirect()->route('players.index')
            ->with('success', '🎉 Player has been successfully added!');
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
            'sport_id'  => 'required|exists:sports,sports_id',
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

        // ✨ Enhanced success message with emoji
        return redirect()->route('players.index')
            ->with('success', '✅ Player has been successfully updated!');
    }

    public function destroy(Player $player)
    {
        $playerName = $player->name;
        $player->delete();
        
        // ✨ Enhanced success message with emoji
        return redirect()->route('players.index')
            ->with('success', '🗑️ ' . $playerName . ' has been successfully deleted!');
    }

    public function stats(Request $request)
    {
        // Base query with player stats averages
        $query = Player::with(['team', 'sport', 'gameStats'])
            ->leftJoin('player_game_stats', 'players.id', '=', 'player_game_stats.player_id')
            ->selectRaw('players.id, players.name, players.team_id, players.sport_id, players.number, players.position, players.created_at, players.updated_at')
            ->selectRaw('ROUND(AVG(player_game_stats.points), 1) as avg_points')
            ->selectRaw('ROUND(AVG(player_game_stats.assists), 1) as avg_assists')
            ->selectRaw('ROUND(AVG(player_game_stats.rebounds), 1) as avg_rebounds')
            ->selectRaw('ROUND(AVG(player_game_stats.steals), 1) as avg_blocks')
            ->selectRaw('ROUND(AVG(player_game_stats.fouls), 1) as avg_fouls')
            ->selectRaw('COUNT(player_game_stats.id) as games_played')
            ->groupBy('players.id', 'players.name', 'players.team_id', 'players.sport_id', 'players.number', 'players.position', 'players.created_at', 'players.updated_at')
            ->having('games_played', '>', 0);

        // Apply filters
        if ($request->filled('team_id') && $request->team_id !== 'all') {
            $query->where('players.team_id', $request->team_id);
        }

        if ($request->filled('sport_id') && $request->sport_id !== 'all') {
            $query->where('players.sport_id', $request->sport_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('players.name', 'like', "%{$search}%")
                  ->orWhere('players.position', 'like', "%{$search}%");
            });
        }

        // Order by average points descending
        $playerStats = $query->orderByDesc('avg_points')->paginate(15)->withQueryString();

        // Get filter options
        $teams = Team::all();
        $sports = Sport::all();

        return view('stats', compact('playerStats', 'teams', 'sports'));
    }
}