<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'division',
        'location',
        'sport',
        'start_date',
        'bracket_type',
    ];

    /**
     * A tournament has many brackets.
     */
    public function brackets()
    {
        return $this->hasMany(Bracket::class);
    }

    /**
     * A tournament has many teams.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
