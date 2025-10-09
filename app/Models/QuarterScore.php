<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuarterScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'quarter',
        'team1_score',
        'team2_score',
    ];

    protected $casts = [
        'quarter' => 'integer',
        'team1_score' => 'integer',
        'team2_score' => 'integer',
    ];

    /**
     * Get the game this quarter score belongs to
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Scope: Order by quarter
     */
    public function scopeInOrder($query)
    {
        return $query->orderBy('quarter', 'asc');
    }

    /**
     * Get the winner of this quarter
     */
    public function getQuarterWinnerAttribute()
    {
        if ($this->team1_score > $this->team2_score) {
            return 'team1';
        } elseif ($this->team2_score > $this->team1_score) {
            return 'team2';
        }
        return 'tie';
    }

    /**
     * Get the point difference for this quarter
     */
    public function getPointDifferenceAttribute()
    {
        return abs($this->team1_score - $this->team2_score);
    }

    /**
     * Get formatted quarter display (1st, 2nd, 3rd, 4th)
     */
    public function getQuarterDisplayAttribute()
    {
        $suffixes = ['', '1st', '2nd', '3rd', '4th'];
        return $suffixes[$this->quarter] ?? $this->quarter . 'th';
    }

    /**
     * Get total score for team 1 up to this quarter
     */
    public function getCumulativeTeam1ScoreAttribute()
    {
        return QuarterScore::where('game_id', $this->game_id)
            ->where('quarter', '<=', $this->quarter)
            ->sum('team1_score');
    }

    /**
     * Get total score for team 2 up to this quarter
     */
    public function getCumulativeTeam2ScoreAttribute()
    {
        return QuarterScore::where('game_id', $this->game_id)
            ->where('quarter', '<=', $this->quarter)
            ->sum('team2_score');
    }
}