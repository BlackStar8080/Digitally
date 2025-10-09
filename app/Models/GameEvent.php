<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'sequence_number',
        'team',
        'player_number',
        'action',
        'points',
        'game_time',
        'period',
        'occurred_at',
    ];

    protected $casts = [
        'sequence_number' => 'integer',
        'points' => 'integer',
        'period' => 'integer',
        'occurred_at' => 'datetime',
    ];

    /**
     * Get the game this event belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Scope: Get events in order
     */
    public function scopeInOrder($query)
    {
        return $query->orderBy('sequence_number', 'asc');
    }

    /**
     * Scope: Get events in reverse order (newest first)
     */
    public function scopeReverseOrder($query)
    {
        return $query->orderBy('sequence_number', 'desc');
    }

    /**
     * Scope: Get events for a specific period
     */
    public function scopeForPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope: Get scoring events only
     */
    public function scopeScoringEvents($query)
    {
        return $query->where('points', '>', 0);
    }

    /**
     * Scope: Get events for a specific team
     */
    public function scopeForTeam($query, $team)
    {
        return $query->where('team', $team);
    }

    /**
     * Scope: Get foul events
     */
    public function scopeFouls($query)
    {
        return $query->where('action', 'like', '%Foul%');
    }

    /**
     * Check if this is a system event
     */
    public function isSystemEvent()
    {
        return $this->team === 'GAME' || $this->player_number === 'SYSTEM';
    }

    /**
     * Check if this is a scoring event
     */
    public function isScoringEvent()
    {
        return $this->points > 0;
    }

    /**
     * Get formatted period display (Q1, Q2, etc.)
     */
    public function getPeriodDisplayAttribute()
    {
        return 'Q' . $this->period;
    }
}