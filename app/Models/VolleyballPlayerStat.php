<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolleyballPlayerStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'player_id',
        'team_id',
        'kills',
        'aces',
        'blocks',
        'digs',
        'assists',
        'errors',
        'service_errors',
        'attack_attempts',
        'block_assists',
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
     * Calculate hitting percentage (kills - errors) / total attempts
     */
    public function getHittingPercentage()
    {
        if ($this->attack_attempts === 0) return 0;
        return round((($this->kills - $this->errors) / $this->attack_attempts) * 100, 1);
    }

    /**
     * Get total points contributed (kills + aces + blocks)
     */
    public function getTotalPoints()
    {
        return $this->kills + $this->aces + $this->blocks;
    }

    /**
     * Get MVP score for ranking
     */
    public function getMVPScore()
    {
        // Formula: (kills * 3) + (aces * 2) + (blocks * 2) + (digs * 1) + (assists * 1) - (errors * 2)
        return ($this->kills * 3) + ($this->aces * 2) + ($this->blocks * 2) + 
               ($this->digs * 1) + ($this->assists * 1) - ($this->errors * 2);
    }
}