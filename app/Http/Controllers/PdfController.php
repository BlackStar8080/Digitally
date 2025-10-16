<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Game;

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
        $pdf->setPaper('legal', 'portrait');

        $filename = sprintf(
            'basketball-scoresheet-%s-vs-%s-game%d.pdf',
            str_replace(' ', '-', strtolower($game->team1->team_name)),
            str_replace(' ', '-', strtolower($game->team2->team_name)),
            $game->id
        );

        return $pdf->download($filename);
    }

    public function downloadBracketPdf($id)
{
    $tournament = Tournament::with('rounds.games.teams')->findOrFail($id);

    $pdf = Pdf::loadView('pdf.bracket', compact('tournament'))
              ->setPaper('a4', 'landscape');

    return $pdf->download("{$tournament->name}_bracket.pdf");
}

    /**
     * Stream basketball scoresheet (view in browser)
     */
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
        $pdf->setPaper('legal', 'portrait');

        return $pdf->stream('basketball-scoresheet-game-' . $game->id . '.pdf');
    }

    /**
 * Stream volleyball scoresheet (view in browser)
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

    // Load saved tallysheet data
    $liveData = [];
    if ($game->volleyballTallysheet) {
        $liveData = [
            'team1_sets_won' => $game->volleyballTallysheet->team1_sets_won ?? 0,
            'team2_sets_won' => $game->volleyballTallysheet->team2_sets_won ?? 0,
            'set_scores' => $game->volleyballTallysheet->set_scores ?? [],
            'events' => $game->volleyballTallysheet->game_events ?? [],
            'running_scores' => $game->volleyballTallysheet->running_scores ?? [],
        ];
    }

    // Convert logos to base64 if needed
    $logoLeft = '';
    $logoRight = '';
    $leftPath = public_path('images/logo/tagoloan-flag.png');
    $rightPath = public_path('images/logo/mayor-logo.png');
    if (file_exists($leftPath)) $logoLeft = base64_encode(file_get_contents($leftPath));
    if (file_exists($rightPath)) $logoRight = base64_encode(file_get_contents($rightPath));

    $isPdf = true;

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('games.volleyball-scoresheet', compact(
        'game',
        'team1Players',
        'team2Players',
        'liveData',
        'isPdf',
        'logoLeft',
        'logoRight'
    ))->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'dpi' => 150,
        'enable_font_subsetting' => true,
    ]);

    $pdf->getDomPDF()->getOptions()->set('isUnicodeEnabled', true);
    $pdf->setPaper('legal', 'landscape'); // ✅ Long bondpaper, landscape

    return $pdf->stream('volleyball-scoresheet-game-' . $game->id . '.pdf');
}

}