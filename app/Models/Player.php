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
        'number',
        'sport',
        'position',
        'age',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function gameStats()
    {
        return $this->hasMany(PlayerGameStat::class);
    }
}