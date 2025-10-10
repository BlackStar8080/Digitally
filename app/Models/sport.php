<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $primaryKey = 'sports_id';
    
    protected $fillable = [
        'sports_name',
        'sports_details',
    ];

    public function players()
    {
        return $this->hasMany(Player::class, 'sport_id', 'sports_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'sport_id', 'sports_id');
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'sport_id', 'sports_id');
    }
}