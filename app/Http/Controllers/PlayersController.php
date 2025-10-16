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

    // 🔍 Filters
    if ($request->filled('team_id') && $request->team_id !== 'all') {
        $query->where('team_id', $request->team_id);
    }

    if ($request->filled('sport_id') && $request->sport_id !== 'all') {
        $query->where('sport_id', $request->sport_id);
    }

    if ($request->filled('position') && $request->position !== 'all') {
        $query->where('position', $request->position);
    }

    // ✅ Sanitize and validate search input
    if ($request->filled('search')) {
        $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $request->search); // removes special chars

        // ignore search if it's empty after removing special chars
        if (!empty(trim($search))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhereHas('team', fn($team) =>
                      $team->where('team_name', 'like', "%{$search}%")
                  )
                  ->orWhereHas('sport', fn($sport) =>
                      $sport->where('sports_name', 'like', "%{$search}%")
                  );
            });
        }
    }

    $players = $query->orderBy('name')->paginate(15)->withQueryString();

    $teams = Team::all();
    $sports = Sport::all();
    $positions = Player::select('position')
        ->whereNotNull('position')
        ->distinct()
        ->pluck('position');

    return view('players', compact('players', 'teams', 'sports', 'positions'));
}



    // Store new player
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => [
            'required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/',
            Rule::unique('players', 'name')->where(function ($query) use ($request) {
                return $query->where('team_id', $request->team_id);
            }),
        ],
        'team_id'   => 'required|exists:teams,id',
        'sport_id'  => 'required|exists:sports,sports_id',
        'number'    => 'nullable|integer',
        'position'  => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s]+$/',
        'birthday'  => 'required|date',
    ], [
        'name.unique' => 'This player already exists in the selected team.',
        'name.regex' => 'Player name can only contain letters, numbers, and spaces.',
        'position.regex' => 'Position name can only contain letters, numbers, and spaces.',
    ]);

    // Compute age from birthday
    $birthday = $request->input('birthday');
    $age = $birthday ? \Carbon\Carbon::parse($birthday)->age : null;

    $validated['age'] = $age;

    Player::create($validated);

    return redirect()->route('players.index')
        ->with('success', '🎉 Player has been successfully added!');
}

    public function update(Request $request, Player $player)
{
    $validated = $request->validate([
        'name' => [
            'required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/',
            Rule::unique('players', 'name')->where(function ($query) use ($request, $player) {
                return $query->where('team_id', $request->team_id)
                             ->where('id', '!=', $player->id);
            }),
        ],
        'team_id'   => 'required|exists:teams,id',
        'sport_id'  => 'required|exists:sports,sports_id',
        'number'    => 'nullable|integer',
        'position'  => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\s]+$/',
        'birthday'  => 'required|date',
    ], [
        'name.unique' => 'This player already exists in the selected team.',
        'name.regex' => 'Player name can only contain letters, numbers, and spaces.',
        'position.regex' => 'Position name can only contain letters, numbers, and spaces.',
    ]);

    $birthday = $request->input('birthday');
    $age = $birthday ? \Carbon\Carbon::parse($birthday)->age : null;

    $validated['age'] = $age;

    $player->update($validated);

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

    public function checkPlayer(Request $request)
{
    $name = preg_replace('/[^a-zA-Z0-9\s]/', '', $request->name); // remove special chars
    $teamId = $request->team_id;

    $exists = Player::where('team_id', $teamId)
        ->whereRaw('LOWER(name) = ?', [strtolower($name)])
        ->exists();

    return response()->json(['exists' => $exists]);
}


    public function stats(Request $request)
{
    // Determine sport type from filter or default to basketball
    $sportId = $request->filled('sport_id') && $request->sport_id !== 'all' 
        ? $request->sport_id 
        : null;
    
    $sport = null;
    $isVolleyball = false;
    
    if ($sportId) {
        $sport = Sport::find($sportId);
        $isVolleyball = $sport && strtolower($sport->sports_name) === 'volleyball';
    }

    if ($isVolleyball) {
        // VOLLEYBALL STATS QUERY
        $query = Player::with(['team', 'sport', 'volleyballGameStats'])
            ->leftJoin('volleyball_player_stats', 'players.id', '=', 'volleyball_player_stats.player_id')
            ->selectRaw('players.id, players.name, players.team_id, players.sport_id, players.number, players.position, players.created_at, players.updated_at')
            ->selectRaw('ROUND(AVG(volleyball_player_stats.kills), 1) as avg_kills')
            ->selectRaw('ROUND(AVG(volleyball_player_stats.aces), 1) as avg_aces')
            ->selectRaw('ROUND(AVG(volleyball_player_stats.blocks), 1) as avg_blocks')
            ->selectRaw('ROUND(AVG(volleyball_player_stats.digs), 1) as avg_digs')
            ->selectRaw('ROUND(AVG(volleyball_player_stats.assists), 1) as avg_assists')
            ->selectRaw('ROUND(AVG(volleyball_player_stats.errors), 1) as avg_errors')
            ->selectRaw('COUNT(volleyball_player_stats.id) as games_played')
            ->groupBy('players.id', 'players.name', 'players.team_id', 'players.sport_id', 'players.number', 'players.position', 'players.created_at', 'players.updated_at')
            ->having('games_played', '>', 0);

        // Apply filters
        if ($request->filled('team_id') && $request->team_id !== 'all') {
            $query->where('players.team_id', $request->team_id);
        }

        if ($sportId) {
            $query->where('players.sport_id', $sportId);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('players.name', 'like', "%{$search}%")
                  ->orWhere('players.position', 'like', "%{$search}%");
            });
        }

        // Order by average kills descending
        $playerStats = $query->orderByDesc('avg_kills')->paginate(15)->withQueryString();
        
    } else {
        // BASKETBALL STATS QUERY (Original)
        $query = Player::with(['team', 'sport', 'gameStats'])
            ->leftJoin('player_game_stats', 'players.id', '=', 'player_game_stats.player_id')
            ->selectRaw('players.id, players.name, players.team_id, players.sport_id, players.number, players.position, players.created_at, players.updated_at')
            ->selectRaw('ROUND(AVG(player_game_stats.points), 1) as avg_points')
            ->selectRaw('ROUND(AVG(player_game_stats.assists), 1) as avg_assists')
            ->selectRaw('ROUND(AVG(player_game_stats.rebounds), 1) as avg_rebounds')
            ->selectRaw('ROUND(AVG(player_game_stats.steals), 1) as avg_steals')
            ->selectRaw('ROUND(AVG(player_game_stats.fouls), 1) as avg_fouls')
            ->selectRaw('COUNT(player_game_stats.id) as games_played')
            ->groupBy('players.id', 'players.name', 'players.team_id', 'players.sport_id', 'players.number', 'players.position', 'players.created_at', 'players.updated_at')
            ->having('games_played', '>', 0);

        // Apply filters
        if ($request->filled('team_id') && $request->team_id !== 'all') {
            $query->where('players.team_id', $request->team_id);
        }

        if ($sportId) {
            $query->where('players.sport_id', $sportId);
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
    }

    // Get filter options
    $teams = Team::all();
    $sports = Sport::all();

    return view('stats', compact('playerStats', 'teams', 'sports', 'isVolleyball'));
}
}