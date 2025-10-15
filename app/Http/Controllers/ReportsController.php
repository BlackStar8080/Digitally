<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tallysheet;
use App\Models\VolleyballTallysheet;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $tournamentFilter = $request->get('tournament');
        $sportFilter = $request->get('sport');
        $scorekeeperFilter = $request->get('scorekeeper');
        $searchQuery = $request->get('search');

        // Query basketball tallysheets
        $basketballLogs = Tallysheet::query()
            ->join('games', 'tallysheets.game_id', '=', 'games.id')
            ->join('brackets', 'games.bracket_id', '=', 'brackets.id')
            ->join('tournaments', 'brackets.tournament_id', '=', 'tournaments.id')
            ->join('sports', 'tournaments.sport_id', '=', 'sports.sports_id') // Fixed: sports_id not id
            ->leftJoin('users', 'tallysheets.user_id', '=', 'users.id')
            ->select(
                'tallysheets.id as tally_sheet_id',
                'games.id as match_id',
                'users.name as scorekeeper_name',
                'users.email as email',
                'tallysheets.created_at as submitted_date',
                'tournaments.name as tournament_name',
                'sports.sports_name as sport',
                'tournaments.id as tournament_id',
                'users.id as scorekeeper_id'
            )
            ->where('games.status', 'completed');

        // Query volleyball tallysheets
        $volleyballLogs = VolleyballTallysheet::query()
            ->join('games', 'volleyball_tallysheets.game_id', '=', 'games.id')
            ->join('brackets', 'games.bracket_id', '=', 'brackets.id')
            ->join('tournaments', 'brackets.tournament_id', '=', 'tournaments.id')
            ->join('sports', 'tournaments.sport_id', '=', 'sports.sports_id') // Fixed: sports_id not id
            ->leftJoin('users', 'volleyball_tallysheets.user_id', '=', 'users.id')
            ->select(
                'volleyball_tallysheets.id as tally_sheet_id',
                'games.id as match_id',
                'users.name as scorekeeper_name',
                'users.email as email',
                'volleyball_tallysheets.created_at as submitted_date',
                'tournaments.name as tournament_name',
                'sports.sports_name as sport',
                'tournaments.id as tournament_id',
                'users.id as scorekeeper_id'
            )
            ->where('games.status', 'completed');

        // Apply filters to both queries
        if ($tournamentFilter) {
            $basketballLogs->where('tournaments.id', $tournamentFilter);
            $volleyballLogs->where('tournaments.id', $tournamentFilter);
        }

        if ($sportFilter) {
            $basketballLogs->where('sports.sports_name', $sportFilter);
            $volleyballLogs->where('sports.sports_name', $sportFilter);
        }

        if ($scorekeeperFilter) {
            $basketballLogs->where('users.id', $scorekeeperFilter);
            $volleyballLogs->where('users.id', $scorekeeperFilter);
        }

        if ($searchQuery) {
            $basketballLogs->where(function($query) use ($searchQuery) {
                $query->where('users.name', 'like', "%{$searchQuery}%")
                      ->orWhere('users.email', 'like', "%{$searchQuery}%")
                      ->orWhere('tournaments.name', 'like', "%{$searchQuery}%")
                      ->orWhere('games.id', 'like', "%{$searchQuery}%");
            });

            $volleyballLogs->where(function($query) use ($searchQuery) {
                $query->where('users.name', 'like', "%{$searchQuery}%")
                      ->orWhere('users.email', 'like', "%{$searchQuery}%")
                      ->orWhere('tournaments.name', 'like', "%{$searchQuery}%")
                      ->orWhere('games.id', 'like', "%{$searchQuery}%");
            });
        }

        // Combine both queries using union
        $allLogs = $basketballLogs->union($volleyballLogs)
            ->orderBy('submitted_date', 'desc');

        // Paginate results
        $logs = DB::table(DB::raw("({$allLogs->toSql()}) as combined_logs"))
            ->mergeBindings($allLogs->getQuery())
            ->paginate(15)
            ->withQueryString();

        // Transform the paginated items to use Carbon for date formatting
        $logs->getCollection()->transform(function ($log) {
            $log->submitted_date = \Carbon\Carbon::parse($log->submitted_date);
            return $log;
        });

        // Calculate statistics
        $totalLogs = DB::table(DB::raw("({$allLogs->toSql()}) as combined_logs"))
            ->mergeBindings($allLogs->getQuery())
            ->count();

        $activeScorekeepers = DB::table(DB::raw("({$allLogs->toSql()}) as combined_logs"))
            ->mergeBindings($allLogs->getQuery())
            ->whereNotNull('scorekeeper_id')
            ->distinct('scorekeeper_id')
            ->count('scorekeeper_id');

        $sportsCovered = DB::table(DB::raw("({$allLogs->toSql()}) as combined_logs"))
            ->mergeBindings($allLogs->getQuery())
            ->distinct('sport')
            ->count('sport');

        $tournamentsCount = DB::table(DB::raw("({$allLogs->toSql()}) as combined_logs"))
            ->mergeBindings($allLogs->getQuery())
            ->distinct('tournament_id')
            ->count('tournament_id');

        // Get filter dropdown data
        $tournaments = Tournament::orderBy('name')->get();
        
        $sports = DB::table('sports')
            ->join('tournaments', 'sports.sports_id', '=', 'tournaments.sport_id') // Fixed
            ->join('brackets', 'tournaments.id', '=', 'brackets.tournament_id')
            ->join('games', 'brackets.id', '=', 'games.bracket_id')
            ->where('games.status', 'completed')
            ->distinct()
            ->pluck('sports.sports_name');

        $scorekeepers = User::whereHas('tallysheets')
            ->orWhereHas('volleyballTallysheets')
            ->orderBy('name')
            ->get();

        return view('reports', compact(
            'logs',
            'totalLogs',
            'activeScorekeepers',
            'sportsCovered',
            'tournamentsCount',
            'tournaments',
            'sports',
            'scorekeepers'
        ));
    }
}