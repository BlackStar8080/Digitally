<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = ['name', 'type'];

    public function players() {
        return $this->hasMany(Player::class);
    }

    public function teams() {
        return $this->hasMany(Team::class);
    }

    public function tournaments() {
        return $this->hasMany(Tournament::class);
    }
}

