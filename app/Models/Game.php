<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'bracket_id',
        'round',
        'match_number',
        'team1_id',
        'team2_id',
        'winner_id',
        'team1_score',
        'team2_score',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'game_details',
        'referee',
        'assistant_referee_1',
        'assistant_referee_2',
        'team1_selected_players',     // This now stores roster + starter data
        'team2_selected_players',     // This now stores roster + starter data
        'game_data',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'game_details' => 'array', // JSON to array conversion
        'team1_selected_players' => 'array', // Add this for easier access
        'team2_selected_players' => 'array', // Add this for easier access
        'game_data' => 'array',
    ];

    /**
     * A game belongs to a bracket.
     */
    public function bracket()
    {
        return $this->belongsTo(Bracket::class);
    }

    /**
     * Team 1 relationship.
     */
    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    /**
     * Team 2 relationship.
     */
    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    /**
     * Winner relationship.
     */
    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    /**
     * Get the losing team.
     */
    public function getLoser()
    {
        if (!$this->winner_id) return null;
        
        $loserId = $this->winner_id === $this->team1_id ?
                   $this->team2_id : $this->team1_id;
                   
        return Team::find($loserId);
    }

    /**
     * Check if game has both teams assigned.
     */
    public function isReady()
    {
        return $this->team1_id && $this->team2_id;
    }

    /**
     * Check if game is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Get formatted score display.
     */
    public function getScoreDisplay()
    {
        if (!$this->isCompleted()) {
            return 'TBD';
        }
        return "{$this->team1_score} - {$this->team2_score}";
    }

    /**
     * Get game display name.
     */
    public function getDisplayName()
    {
        if ($this->round === 1) {
            return "Round 1 - Game {$this->match_number}";
        }

        $bracket = $this->bracket;
        $totalRounds = $bracket->getTotalRounds();

        if ($this->round === $totalRounds) {
            return "Finals";
        } elseif ($this->round === $totalRounds - 1) {
            return "Semifinals - Game {$this->match_number}";
        } elseif ($this->round === $totalRounds - 2) {
            return "Quarterfinals - Game {$this->match_number}";
        }

        return "Round {$this->round} - Game {$this->match_number}";
    }

    /**
     * Update game score and determine winner.
     */
    public function updateScore($team1Score, $team2Score)
    {
        // Prevent tie scores
        if ($team1Score === $team2Score) {
            throw new \InvalidArgumentException('Tie scores are not allowed. One team must win.');
        }

        $this->team1_score = $team1Score;
        $this->team2_score = $team2Score;

        // Determine winner
        if ($team1Score > $team2Score) {
            $this->winner_id = $this->team1_id;
        } else {
            $this->winner_id = $this->team2_id;
        }

        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();

        return $this;
    }

    // Helper methods for roster/starter data
    public function getTeam1RosterIds()
    {
        return $this->team1_selected_players['roster'] ?? [];
    }

    public function getTeam1StarterIds()
    {
        return $this->team1_selected_players['starters'] ?? [];
    }

    public function getTeam2RosterIds()
    {
        return $this->team2_selected_players['roster'] ?? [];
    }

    public function getTeam2StarterIds()
    {
        return $this->team2_selected_players['starters'] ?? [];
    }

    // Game data helpers
    public function getTeam1Fouls()
    {
        return $this->game_data['team1_fouls'] ?? 0;
    }

    public function getTeam2Fouls()
    {
        return $this->game_data['team2_fouls'] ?? 0;
    }

    public function getTeam1Timeouts()
    {
        return $this->game_data['team1_timeouts'] ?? 0;
    }

    public function getTeam2Timeouts()
    {
        return $this->game_data['team2_timeouts'] ?? 0;
    }

    public function getTotalQuarters()
    {
        return $this->game_data['total_quarters'] ?? 4;
    }

    public function getGameEvents()
    {
        return $this->game_data['game_events'] ?? [];
    }

    public function getPeriodScores()
    {
        return $this->game_data['period_scores'] ?? [
            'team1' => [0, 0, 0, 0],
            'team2' => [0, 0, 0, 0]
        ];
    }

    public function wasCompletedByScoresheet()
    {
        return $this->game_data['completed_by_scoresheet'] ?? false;
    }

    public function hasDetailedStats()
    {
        return !empty($this->game_data['game_events']) || $this->wasCompletedByScoresheet();
    }

    /**
 * Get player statistics for this game
 */
public function playerStats()
{
    return $this->hasMany(PlayerGameStat::class);
}

/**
 * Get the MVP of this game
 */
public function getMVP()
{
    return $this->playerStats()->where('is_mvp', true)->first();
}

/**
 * Check if this is a bye game
 */
public function isBye()
{
    return $this->is_bye === true || $this->team2_id === null;
}

/**
 * Get the team that received the bye
 */
public function getByeTeam()
{
    if ($this->isBye()) {
        return $this->team1_id ? $this->team1 : null;
    }
    return null;
}

public function tallysheet()
{
    return $this->hasOne(Tallysheet::class);
}

// Add to your existing Game model

/**
 * Volleyball player statistics
 */
public function volleyballPlayerStats()
{
    return $this->hasMany(VolleyballPlayerStat::class);
}

/**
 * Volleyball tallysheet
 */
public function volleyballTallysheet()
{
    return $this->hasOne(VolleyballTallysheet::class);
}

/**
 * Check if this is a volleyball game
 */
public function isVolleyball()
{
    return $this->bracket && 
           $this->bracket->tournament && 
           $this->bracket->tournament->sport && 
           strtolower($this->bracket->tournament->sport->sports_name) === 'volleyball';
}

/**
 * Check if this is a basketball game
 */
public function isBasketball()
{
    return $this->bracket && 
           $this->bracket->tournament && 
           $this->bracket->tournament->sport && 
           strtolower($this->bracket->tournament->sport->sports_name) === 'basketball';
}

/**
 * Get the appropriate stats based on sport type
 */
public function getSportStats()
{
    if ($this->isVolleyball()) {
        return $this->volleyballPlayerStats;
    }
    return $this->playerStats; // Basketball stats
}

/**
 * Get the appropriate tallysheet based on sport type
 */
public function getSportTallysheet()
{
    if ($this->isVolleyball()) {
        return $this->volleyballTallysheet;
    }
    return $this->tallysheet; // Basketball tallysheet
}

}