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
        'sport_id',
        'number',
        'position',
        'age',
        'birthday',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id', 'sports_id');
    }

    public function gameStats()
    {
        return $this->hasMany(PlayerGameStat::class);
    }

    // ADD THIS
    public function volleyballGameStats()
    {
        return $this->hasMany(VolleyballPlayerStat::class);
    }
}