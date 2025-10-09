<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team_id',
        'sports_id', // ✅ Changed from 'sport' to 'sports_id'
        'number',
        'position',
        'age',
    ];

    /**
     * Get the team that owns the player
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the sport that this player plays
     */
    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sports_id', 'sports_id');
    }

    /**
     * Get the player's game stats
     */
    public function gameStats()
    {
        return $this->hasMany(PlayerGameStat::class);
    }
}