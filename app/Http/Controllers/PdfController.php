<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Game;
use App\Models\Tournament;
class PdfController extends Controller
{
    /**
     * Download basketball scoresheet as PDF
     */

    
    public function basketballScoresheet($gameId)
    {
        $game = Game::with([
            'team1.players', 
            'team2.players', 
            'bracket.tournament',
            'tallysheet',
            'playerStats.player'  // ✅ ADD THIS - Load player stats with player info
        ])->findOrFail($gameId);
        
        $team1Data = json_decode($game->team1_selected_players, true) ?? [];
        $team2Data = json_decode($game->team2_selected_players, true) ?? [];
        
        $team1RosterIds = $team1Data['roster'] ?? [];
        $team2RosterIds = $team2Data['roster'] ?? [];

        $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
            return in_array($player->id, $team1RosterIds);
        })->sortBy('number');
        
        $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
            return in_array($player->id, $team2RosterIds);
        })->sortBy('number');
        
        $liveData = [];
        
        if ($game->tallysheet) {
            $liveData = [
                'team1_score' => $game->tallysheet->team1_score ?? 0,
                'team2_score' => $game->tallysheet->team2_score ?? 0,
                'team1_fouls' => $game->tallysheet->team1_fouls ?? 0,
                'team2_fouls' => $game->tallysheet->team2_fouls ?? 0,
                'team1_timeouts' => $game->tallysheet->team1_timeouts ?? 0,
                'team2_timeouts' => $game->tallysheet->team2_timeouts ?? 0,
                'events' => $game->tallysheet->game_events ?? [],
                'period_scores' => $game->tallysheet->period_scores ?? [
                    'team1' => [0, 0, 0, 0], 
                    'team2' => [0, 0, 0, 0]
                ]
            ];
        } else {
            $liveData = [
                'team1_score' => $game->team1_score ?? 0,
                'team2_score' => $game->team2_score ?? 0,
                'team1_fouls' => $game->getTeam1Fouls(),
                'team2_fouls' => $game->getTeam2Fouls(),
                'team1_timeouts' => $game->getTeam1Timeouts(),
                'team2_timeouts' => $game->getTeam2Timeouts(),
                'events' => $game->getGameEvents(),
                'period_scores' => $game->getPeriodScores()
            ];
        }

        // ✅ GET MVP/BEST PLAYER DATA
        $mvpPlayer = $game->playerStats()
            ->where('is_mvp', true)
            ->with('player')
            ->first();

        // Convert images to base64 for PDF
        $logoLeft = '';
        $logoRight = '';
        
        $leftPath = public_path('images/logo/tagoloan-flag.png');
        $rightPath = public_path('images/logo/mayor-logo.png');
        
        if (file_exists($leftPath)) {
            $logoLeft = base64_encode(file_get_contents($leftPath));
        }
        
        if (file_exists($rightPath)) {
            $logoRight = base64_encode(file_get_contents($rightPath));
        }

        $isPdf = true;

        $pdf = Pdf::loadView('games.basketball-scoresheet', compact(
            'game', 
            'team1Players', 
            'team2Players', 
            'liveData',
            'isPdf',
            'logoLeft',
            'logoRight',
            'mvpPlayer'  // ✅ ADD THIS
        ))->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'dpi' => 150,
            'enable_font_subsetting' => true,
        ]);

        $pdf->getDomPDF()->getOptions()->set('isUnicodeEnabled', true);
        $pdf->setPaper('folio', 'portrait');

        $filename = sprintf(
            'basketball-scoresheet-%s-vs-%s-game%d.pdf',
            str_replace(' ', '-', strtolower($game->team1->team_name)),
            str_replace(' ', '-', strtolower($game->team2->team_name)),
            $game->id
        );

        return $pdf->download($filename);
    }



    public function viewBasketballScoresheet($gameId)
    {
        $game = Game::with([
            'team1.players', 
            'team2.players', 
            'bracket.tournament',
            'tallysheet',
            'playerStats.player'  // ✅ ADD THIS
        ])->findOrFail($gameId);
        
        $team1Data = json_decode($game->team1_selected_players, true) ?? [];
        $team2Data = json_decode($game->team2_selected_players, true) ?? [];
        
        $team1RosterIds = $team1Data['roster'] ?? [];
        $team2RosterIds = $team2Data['roster'] ?? [];

        $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
            return in_array($player->id, $team1RosterIds);
        })->sortBy('number');
        
        $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
            return in_array($player->id, $team2RosterIds);
        })->sortBy('number');
        
        $liveData = [];
        
        if ($game->tallysheet) {
            $liveData = [
                'team1_score' => $game->tallysheet->team1_score ?? 0,
                'team2_score' => $game->tallysheet->team2_score ?? 0,
                'team1_fouls' => $game->tallysheet->team1_fouls ?? 0,
                'team2_fouls' => $game->tallysheet->team2_fouls ?? 0,
                'team1_timeouts' => $game->tallysheet->team1_timeouts ?? 0,
                'team2_timeouts' => $game->tallysheet->team2_timeouts ?? 0,
                'events' => $game->tallysheet->game_events ?? [],
                'period_scores' => $game->tallysheet->period_scores ?? [
                    'team1' => [0, 0, 0, 0], 
                    'team2' => [0, 0, 0, 0]
                ]
            ];
        } else {
            $liveData = [
                'team1_score' => $game->team1_score ?? 0,
                'team2_score' => $game->team2_score ?? 0,
                'team1_fouls' => $game->getTeam1Fouls(),
                'team2_fouls' => $game->getTeam2Fouls(),
                'team1_timeouts' => $game->getTeam1Timeouts(),
                'team2_timeouts' => $game->getTeam2Timeouts(),
                'events' => $game->getGameEvents(),
                'period_scores' => $game->getPeriodScores()
            ];
        }

        // ✅ GET MVP/BEST PLAYER DATA
        $mvpPlayer = $game->playerStats()
            ->where('is_mvp', true)
            ->with('player')
            ->first();

        // Convert images to base64
        $logoLeft = '';
        $logoRight = '';
        
        $leftPath = public_path('images/logo/tagoloan-flag.png');
        $rightPath = public_path('images/logo/mayor-logo.png');
        
        if (file_exists($leftPath)) {
            $logoLeft = base64_encode(file_get_contents($leftPath));
        }
        
        if (file_exists($rightPath)) {
            $logoRight = base64_encode(file_get_contents($rightPath));
        }

        $isPdf = true;

        $pdf = Pdf::loadView('games.basketball-scoresheet', compact(
            'game', 
            'team1Players', 
            'team2Players', 
            'liveData',
            'isPdf',
            'logoLeft',
            'logoRight',
            'mvpPlayer'  // ✅ ADD THIS
        ))->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'dpi' => 150,
            'enable_font_subsetting' => true,
        ]);

        $pdf->getDomPDF()->getOptions()->set('isUnicodeEnabled', true);
        $pdf->setPaper('folio', 'portrait');

        return $pdf->stream('basketball-scoresheet-game-' . $game->id . '.pdf');
    }

    /**
 * Stream volleyball scoresheet (view in browser)
 */
/**
 * Download volleyball scoresheet as PDF
 */
public function volleyballScoresheet($gameId)
{
    $game = \App\Models\Game::with([
        'team1.players',
        'team2.players',
        'bracket.tournament',
        'volleyballTallysheet',
        'volleyballPlayerStats.player'
    ])->findOrFail($gameId);

    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];

    $team1Players = $game->team1->players->filter(fn($p) => in_array($p->id, $team1RosterIds))->sortBy('number');
    $team2Players = $game->team2->players->filter(fn($p) => in_array($p->id, $team2RosterIds))->sortBy('number');

    // Load saved tallysheet data
    $liveData = [];
    if ($game->volleyballTallysheet) {
        $liveData = [
            'team1_sets_won' => $game->volleyballTallysheet->team1_sets_won ?? 0,
            'team2_sets_won' => $game->volleyballTallysheet->team2_sets_won ?? 0,
            'team1_score' => $game->volleyballTallysheet->team1_sets_won ?? 0,
            'team2_score' => $game->volleyballTallysheet->team2_sets_won ?? 0,
            'set_scores' => $game->volleyballTallysheet->set_scores ?? [],
            'events' => $game->volleyballTallysheet->game_events ?? [],
            'running_scores' => $game->volleyballTallysheet->running_scores ?? [],
            'team1_timeouts' => $game->volleyballTallysheet->team1_timeouts ?? 0,
            'team2_timeouts' => $game->volleyballTallysheet->team2_timeouts ?? 0,
            'team1_substitutions' => $game->volleyballTallysheet->team1_substitutions ?? 0,
            'team2_substitutions' => $game->volleyballTallysheet->team2_substitutions ?? 0,
            'best_player_id' => $game->volleyballTallysheet->best_player_id ?? null,
            'best_player_stats' => $game->volleyballTallysheet->best_player_stats ?? [],
            'initial_server' => $game->volleyballTallysheet->initial_server ?? null,
        ];
    }

    $isPdf = true; // ✅ Flag to hide download button in PDF

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('games.volleyball-scoresheet', compact(
        'game',
        'team1Players',
        'team2Players',
        'liveData',
        'isPdf' // ✅ Pass the flag
    ))->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => false,
        'dpi' => 96,
        'enable_font_subsetting' => true,
    ]);

    $pdf->setPaper('legal', 'landscape');

    $filename = sprintf(
        'volleyball-scoresheet-%s-vs-%s-game%d.pdf',
        str_replace(' ', '-', strtolower($game->team1->team_name)),
        str_replace(' ', '-', strtolower($game->team2->team_name)),
        $game->id
    );

    return $pdf->download($filename);
}

/**
 * View volleyball scoresheet in browser (with download button)
 */
public function viewVolleyballScoresheet($gameId)
{
    $game = \App\Models\Game::with([
        'team1.players',
        'team2.players',
        'bracket.tournament',
        'volleyballTallysheet',
        'volleyballPlayerStats.player'
    ])->findOrFail($gameId);

    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];

    $team1Players = $game->team1->players->filter(fn($p) => in_array($p->id, $team1RosterIds))->sortBy('number');
    $team2Players = $game->team2->players->filter(fn($p) => in_array($p->id, $team2RosterIds))->sortBy('number');

    $liveData = [];
    if ($game->volleyballTallysheet) {
        $liveData = [
            'team1_sets_won' => $game->volleyballTallysheet->team1_sets_won ?? 0,
            'team2_sets_won' => $game->volleyballTallysheet->team2_sets_won ?? 0,
            'team1_score' => $game->volleyballTallysheet->team1_sets_won ?? 0,
            'team2_score' => $game->volleyballTallysheet->team2_sets_won ?? 0,
            'set_scores' => $game->volleyballTallysheet->set_scores ?? [],
            'events' => $game->volleyballTallysheet->game_events ?? [],
            'running_scores' => $game->volleyballTallysheet->running_scores ?? [],
            'team1_timeouts' => $game->volleyballTallysheet->team1_timeouts ?? 0,
            'team2_timeouts' => $game->volleyballTallysheet->team2_timeouts ?? 0,
            'team1_substitutions' => $game->volleyballTallysheet->team1_substitutions ?? 0,
            'team2_substitutions' => $game->volleyballTallysheet->team2_substitutions ?? 0,
            'best_player_id' => $game->volleyballTallysheet->best_player_id ?? null,
            'best_player_stats' => $game->volleyballTallysheet->best_player_stats ?? [],
            'initial_server' => $game->volleyballTallysheet->initial_server ?? null,
        ];
    }

    $isPdf = false; // ✅ Show download button in browser view

    return view('games.volleyball-scoresheet', compact(
        'game',
        'team1Players',
        'team2Players',
        'liveData',
        'isPdf' // ✅ Pass the flag
    ));
}


public function downloadBracketPdf($id)
{
    // Load tournament with brackets, games, and teams
    $tournament = Tournament::with(['brackets.games.team1', 'brackets.games.team2'])->findOrFail($id);
    $brackets = $tournament->brackets;

    // Add logos like in basketball scoresheet
    $logoLeft = '';
    $logoRight = '';
    $leftPath = public_path('images/logo/tagoloan-flag.png');
    $rightPath = public_path('images/logo/mayor-logo.png');
    
    if (file_exists($leftPath)) {
        $logoLeft = base64_encode(file_get_contents($leftPath));
    }
    if (file_exists($rightPath)) {
        $logoRight = base64_encode(file_get_contents($rightPath));
    }

    // If no games, populate with sample data for an 8-team single-elimination bracket
    if ($brackets->isEmpty() || $brackets->first()->games->isEmpty()) {
        $sampleTeams = [
            ['id' => 1, 'team_name' => 'Team A'],
            ['id' => 2, 'team_name' => 'Team B'],
            ['id' => 3, 'team_name' => 'Team C'],
            ['id' => 4, 'team_name' => 'Team D'],
            ['id' => 5, 'team_name' => 'Team E'],
            ['id' => 6, 'team_name' => 'Team F'],
            ['id' => 7, 'team_name' => 'Team G'],
            ['id' => 8, 'team_name' => 'Team H'],
        ];

        $sampleGames = [
            ['round' => 1, 'team1_id' => 1, 'team2_id' => 2, 'team1_score' => 75, 'team2_score' => 60, 'winner_id' => 1],
            ['round' => 1, 'team1_id' => 3, 'team2_id' => 4, 'team1_score' => 82, 'team2_score' => 78, 'winner_id' => 3],
            ['round' => 1, 'team1_id' => 5, 'team2_id' => 6, 'team1_score' => 65, 'team2_score' => 70, 'winner_id' => 6],
            ['round' => 1, 'team1_id' => 7, 'team2_id' => 8, 'team1_score' => 55, 'team2_score' => 58, 'winner_id' => 8],
            ['round' => 2, 'team1_id' => 1, 'team2_id' => 3, 'team1_score' => 68, 'team2_score' => 62, 'winner_id' => 1],
            ['round' => 2, 'team1_id' => 6, 'team2_id' => 8, 'team1_score' => 72, 'team2_score' => 70, 'winner_id' => 6],
            ['round' => 3, 'team1_id' => 1, 'team2_id' => 6, 'team1_score' => 80, 'team2_score' => 75, 'winner_id' => 1],
        ];

        $brackets = collect([$tournament->brackets->first() ?? new \App\Models\Bracket(['name' => 'Main Bracket', 'tournament_id' => $id])]);
        $brackets->first()->games = collect($sampleGames)->map(function ($game) use ($sampleTeams) {
            return (object) array_merge($game, [
                'team1' => (object) $sampleTeams[array_search($game['team1_id'], array_column($sampleTeams, 'id'))],
                'team2' => (object) $sampleTeams[array_search($game['team2_id'], array_column($sampleTeams, 'id'))],
                'status' => 'completed',
                'is_bye' => false,
            ]);
        });
    }

    // Generate PDF
    $pdf = Pdf::loadView('brackets.printable', compact('tournament', 'brackets', 'logoLeft', 'logoRight'))
        ->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'dpi' => 96,
            'enable_font_subsetting' => true,
        ])
        ->setPaper('a3', 'landscape');

    $pdf->getDomPDF()->getOptions()->set('isUnicodeEnabled', true);

    return $pdf->download("{$tournament->name}_bracket.pdf");
}



}