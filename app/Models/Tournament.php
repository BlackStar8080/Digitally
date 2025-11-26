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
        'sport_id',
        'start_date',
        'mythical_five',
    ];

    protected $casts = [
        'mythical_five' => 'array',
    ];

    /**
     * A tournament belongs to a sport.
     */
    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id', 'sports_id');
    }

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