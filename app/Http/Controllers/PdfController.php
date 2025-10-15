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
        // Get game data
        $game = Game::with(['team1', 'team2', 'bracket.tournament'])->findOrFail($gameId);
        
        // Get team players
        $team1Players = $game->team1->players()->orderBy('number')->get();
        $team2Players = $game->team2->players()->orderBy('number')->get();
        
        // Get live data
        $liveData = [
            'team1_score' => $game->team1_score ?? 0,
            'team2_score' => $game->team2_score ?? 0,
            'team1_timeouts' => $game->team1_timeouts ?? 0,
            'team2_timeouts' => $game->team2_timeouts ?? 0,
            'events' => $game->events ?? [],
            'period_scores' => $game->period_scores ?? [
                'team1' => [0, 0, 0, 0], 
                'team2' => [0, 0, 0, 0]
            ]
        ];

        /**
         * ✅ MAIN FIX
         * - Forces DomPDF to embed DejaVu Sans (full Unicode)
         * - Enables HTML5 and PHP parsing
         * - Enables UTF-8 encoding so ✓ and / render properly
         */
        $pdf = Pdf::loadView('games.basketball-scoresheet', compact(
            'game', 
            'team1Players', 
            'team2Players', 
            'liveData'
        ))->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'dpi' => 150,
            'enable_font_subsetting' => true,
        ]);

        // ✅ Ensure UTF-8 encoding for the output
        $pdf->getDomPDF()->getOptions()->set('isUnicodeEnabled', true);

        // ✅ Set larger paper (your layout fits better in legal)
        $pdf->setPaper('legal', 'portrait');

        // ✅ Generate filename
        $filename = sprintf(
            'basketball-scoresheet-%s-vs-%s-game%d.pdf',
            str_replace(' ', '-', strtolower($game->team1->team_name)),
            str_replace(' ', '-', strtolower($game->team2->team_name)),
            $game->id
        );

        // ✅ Force re-encode HTML output to UTF-8
        $pdf->getDomPDF()->loadHtml(mb_convert_encoding($pdf->getDomPDF()->outputHtml(), 'HTML-ENTITIES', 'UTF-8'));

        return $pdf->download($filename);
    }

    /**
     * Stream basketball scoresheet (view in browser)
     */
    public function viewBasketballScoresheet($gameId)
    {
        $game = Game::with(['team1', 'team2', 'bracket.tournament'])->findOrFail($gameId);
        $team1Players = $game->team1->players()->orderBy('number')->get();
        $team2Players = $game->team2->players()->orderBy('number')->get();
        
        $liveData = [
            'team1_score' => $game->team1_score ?? 0,
            'team2_score' => $game->team2_score ?? 0,
            'team1_timeouts' => $game->team1_timeouts ?? 0,
            'team2_timeouts' => $game->team2_timeouts ?? 0,
            'events' => $game->events ?? [],
            'period_scores' => $game->period_scores ?? [
                'team1' => [0, 0, 0, 0], 
                'team2' => [0, 0, 0, 0]
            ]
        ];

        $pdf = Pdf::loadView('games.basketball-scoresheet', compact(
            'game', 
            'team1Players', 
            'team2Players', 
            'liveData'
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
}
