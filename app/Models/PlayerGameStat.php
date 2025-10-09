<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerGameStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'player_id',
        'team_id',
        'points',
        'fouls',
        'free_throws_made',
        'free_throws_attempted',
        'two_points_made',
        'two_points_attempted',
        'three_points_made',
        'three_points_attempted',
        'assists',      // ADD THIS
            'steals',       // ADD THIS
            'rebounds', 
        'is_mvp',
    ];

    protected $casts = [
        'is_mvp' => 'boolean',
    ];

    /**
     * Get the game this stat belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the player this stat belongs to
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get the team this stat belongs to
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Calculate field goal percentage
     */
    public function getFieldGoalPercentage()
    {
        $attempted = $this->two_points_attempted + $this->three_points_attempted;
        if ($attempted === 0) return 0;
        
        $made = $this->two_points_made + $this->three_points_made;
        return round(($made / $attempted) * 100, 1);
    }

    /**
     * Calculate free throw percentage
     */
    public function getFreeThrowPercentage()
    {
        if ($this->free_throws_attempted === 0) return 0;
        return round(($this->free_throws_made / $this->free_throws_attempted) * 100, 1);
    }

    /**
     * Get MVP score (for ranking players)
     */
    public function getMVPScore()
    {
        // Simple formula: points are weighted highest, fouls are negative
        return ($this->points * 2) - ($this->fouls * 3);
    }
}