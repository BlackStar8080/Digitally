<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tallysheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'team1_score',
        'team2_score',
        'team1_fouls',
        'team2_fouls',
        'team1_timeouts',
        'team2_timeouts',
        'period_scores',
        'running_scores',
        'game_events',
        'player_fouls',
        'best_player_id',
        'best_player_stats',
    ];

    protected $casts = [
        'period_scores' => 'array',
        'running_scores' => 'array',
        'game_events' => 'array',
        'player_fouls' => 'array',
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
     * Get running score for a specific team and score value
     */
    public function hasRunningScore($team, $score)
    {
        $runningScores = $this->running_scores ?? [];
        
        foreach ($runningScores as $entry) {
            if ($entry['team'] === $team && $entry['score'] === $score) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get player foul count by team and player number
     */
    public function getPlayerFouls($team, $playerNumber)
    {
        $playerKey = "{$team}_{$playerNumber}";
        return $this->player_fouls[$playerKey] ?? 0;
    }
}