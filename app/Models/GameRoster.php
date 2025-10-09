<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoster extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'player_id',
        'team_id',
        'is_starter',
        'position_number',
    ];

    protected $casts = [
        'is_starter' => 'boolean',
        'position_number' => 'integer',
    ];

    /**
     * Get the game this roster belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the player
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get the team
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope: Get only starters
     */
    public function scopeStarters($query)
    {
        return $query->where('is_starter', true)->orderBy('position_number');
    }

    /**
     * Scope: Get only bench players
     */
    public function scopeBench($query)
    {
        return $query->where('is_starter', false);
    }

    /**
     * Scope: Filter by team
     */
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}