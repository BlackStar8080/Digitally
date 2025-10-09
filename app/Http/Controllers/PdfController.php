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
        // Get your game data
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
        
        // Load the view
        $pdf = Pdf::loadView('games.basketball-scoresheet', compact(
            'game', 
            'team1Players', 
            'team2Players', 
            'liveData'
        ));
        
        // Set paper size
        $pdf->setPaper('letter', 'portrait');
        
        // Generate filename
        $filename = sprintf(
            'basketball-scoresheet-%s-vs-%s-game%d.pdf',
            str_replace(' ', '-', strtolower($game->team1->team_name)),
            str_replace(' ', '-', strtolower($game->team2->team_name)),
            $game->id
        );
        
        // Download the PDF
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
        ));
        
        $pdf->setPaper('letter', 'portrait');
        
        // Stream instead of download
        return $pdf->stream('basketball-scoresheet-game-' . $game->id . '.pdf');
    }
    
    // You can add more PDF methods here in the future
    // Example: volleyball scoresheet, tournament bracket, etc.
    
    /**
     * Example: Tournament bracket PDF (for future use)
     */
    public function tournamentBracket($tournamentId)
    {
        // Implementation here
    }
}