<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tallysheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'best_player_id',
        'best_player_stats',
    ];

    protected $casts = [
        'best_player_stats' => 'array',
    ];

    /**
     * Get the game this tallysheet belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the best player
     */
    public function bestPlayer()
    {
        return $this->belongsTo(Player::class, 'best_player_id');
    }

    /**
     * Get running score from game events (helper method)
     */
    public function getRunningScores()
    {
        $game = $this->game;
        if (!$game) return [];

        $runningScores = [];
        $scoreA = 0;
        $scoreB = 0;
        
        $events = $game->getEventsInOrder();
        
        foreach ($events as $event) {
            if ($event->points > 0) {
                if ($event->team === 'A') {
                    $scoreA += $event->points;
                    if ($scoreA <= 160) {
                        $runningScores[] = [
                            'team' => 'A',
                            'score' => $scoreA,
                            'sequence' => count($runningScores) + 1
                        ];
                    }
                } else if ($event->team === 'B') {
                    $scoreB += $event->points;
                    if ($scoreB <= 160) {
                        $runningScores[] = [
                            'team' => 'B',
                            'score' => $scoreB,
                            'sequence' => count($runningScores) + 1
                        ];
                    }
                }
            }
        }
        
        return $runningScores;
    }

    /**
     * Helper: Check if a specific running score exists
     */
    public function hasRunningScore($team, $score)
    {
        $runningScores = $this->getRunningScores();
        
        foreach ($runningScores as $entry) {
            if ($entry['team'] === $team && $entry['score'] === $score) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get player foul count from game events (helper method)
     */
    public function getPlayerFouls($team, $playerNumber)
    {
        $game = $this->game;
        if (!$game) return 0;

        return $game->gameEvents()
            ->where('team', $team)
            ->where('player_number', $playerNumber)
            ->where('action', 'like', '%Foul%')
            ->count();
    }
}