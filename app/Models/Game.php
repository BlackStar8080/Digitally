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
        'is_bye',
        'team1_fouls',      // NEW
        'team2_fouls',      // NEW
        'team1_timeouts',   // NEW
        'team2_timeouts',   // NEW
        'total_quarters',   // NEW
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'game_details' => 'array',
        'is_bye' => 'boolean',
        'team1_fouls' => 'integer',      // NEW
        'team2_fouls' => 'integer',      // NEW
        'team1_timeouts' => 'integer',   // NEW
        'team2_timeouts' => 'integer',   // NEW
        'total_quarters' => 'integer',   // NEW
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

    // ==========================================
    // NEW: Game Roster Relationships
    // ==========================================

    /**
     * Get all roster entries for this game
     */
    public function gameRosters()
    {
        return $this->hasMany(GameRoster::class);
    }

    /**
     * Get Team 1 roster
     */
    public function team1Roster()
    {
        return $this->gameRosters()->forTeam($this->team1_id);
    }

    /**
     * Get Team 2 roster
     */
    public function team2Roster()
    {
        return $this->gameRosters()->forTeam($this->team2_id);
    }

    /**
     * Get Team 1 starters
     */
    public function team1Starters()
    {
        return $this->team1Roster()->starters();
    }

    /**
     * Get Team 2 starters
     */
    public function team2Starters()
    {
        return $this->team2Roster()->starters();
    }

    /**
     * Get Team 1 roster player IDs
     */
    public function getTeam1RosterIds()
    {
        return $this->team1Roster()->pluck('player_id')->toArray();
    }

    /**
     * Get Team 2 roster player IDs
     */
    public function getTeam2RosterIds()
    {
        return $this->team2Roster()->pluck('player_id')->toArray();
    }

    /**
     * Get Team 1 starter IDs
     */
    public function getTeam1StarterIds()
    {
        return $this->team1Starters()->pluck('player_id')->toArray();
    }

    /**
     * Get Team 2 starter IDs
     */
    public function getTeam2StarterIds()
    {
        return $this->team2Starters()->pluck('player_id')->toArray();
    }

    // ==========================================
    // NEW: Game Events Relationships
    // ==========================================

    /**
     * Get all events for this game
     */
    public function gameEvents()
    {
        return $this->hasMany(GameEvent::class);
    }

    /**
     * Get events in chronological order
     */
    public function getEventsInOrder()
    {
        return $this->gameEvents()->inOrder()->get();
    }

    /**
     * Get events in reverse order (newest first)
     */
    public function getEventsReverseOrder()
    {
        return $this->gameEvents()->reverseOrder()->get();
    }

    /**
     * Get scoring events only
     */
    public function getScoringEvents()
    {
        return $this->gameEvents()->scoringEvents()->get();
    }

    // ==========================================
    // NEW: Quarter Scores Relationships
    // ==========================================

    /**
     * Get quarter scores for this game
     */
    public function quarterScores()
    {
        return $this->hasMany(QuarterScore::class);
    }

    /**
     * Get quarter scores in order
     */
    public function getQuarterScoresInOrder()
    {
        return $this->quarterScores()->inOrder()->get();
    }

    /**
     * Get period scores array (for compatibility)
     */
    public function getPeriodScores()
    {
        $quarters = $this->quarterScores()->inOrder()->get();
        
        return [
            'team1' => $quarters->pluck('team1_score')->toArray(),
            'team2' => $quarters->pluck('team2_score')->toArray(),
        ];
    }

    // ==========================================
    // Player Stats Relationship (existing)
    // ==========================================

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
     * Tallysheet relationship
     */
    public function tallysheet()
    {
        return $this->hasOne(Tallysheet::class);
    }

    // ==========================================
    // Game State Helpers
    // ==========================================

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

    /**
     * Check if game has detailed stats (events)
     */
    public function hasDetailedStats()
    {
        return $this->gameEvents()->count() > 0;
    }
}