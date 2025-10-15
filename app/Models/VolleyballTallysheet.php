<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolleyballTallysheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id', // Added
        'team1_sets_won',
        'team2_sets_won',
        'set_scores',
        'team1_timeouts',
        'team2_timeouts',
        'team1_substitutions',
        'team2_substitutions',
        'initial_server',
        'serving_order',
        'game_events',
        'running_scores',
        'best_player_id',
        'best_player_stats',
    ];

    protected $casts = [
        'set_scores' => 'array',
        'serving_order' => 'array',
        'game_events' => 'array',
        'running_scores' => 'array',
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
     * Get the user (scorekeeper) who submitted this tallysheet
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the best player
     */
    public function bestPlayer()
    {
        return $this->belongsTo(Player::class, 'best_player_id');
    }

    /**
     * Get set score by set number
     */
    public function getSetScore($setNumber)
    {
        $setScores = $this->set_scores ?? [];
        foreach ($setScores as $score) {
            if ($score['set'] === $setNumber) {
                return $score;
            }
        }
        return null;
    }

    /**
     * Get winner of a specific set
     */
    public function getSetWinner($setNumber)
    {
        $setScore = $this->getSetScore($setNumber);
        if (!$setScore) return null;
        
        return $setScore['team1'] > $setScore['team2'] ? 'A' : 'B';
    }
}