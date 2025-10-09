<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    protected $primaryKey = 'sports_id';

    protected $fillable = [
        'sports_name',
        'sports_details',
    ];

    // Relationships
    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'sports_id', 'sports_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'sports_id', 'sports_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'sports_id', 'sports_id');
    }
}