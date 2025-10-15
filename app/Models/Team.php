<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'coach_name',
        'contact',
        'address',
        'sport_id',
        'tournament_id',
    ];

    /**
     * A team belongs to a tournament.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * A team belongs to a sport.
     */
    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id', 'sports_id');
    }

    /**
     * Get all players that belong to this team.
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Get all brackets that this team participates in.
     */
    public function brackets()
    {
        return $this->belongsToMany(Bracket::class, 'bracket_team')
                    ->withPivot('seed')
                    ->withTimestamps();
    }

    /**
     * Games where this team is Team 1.
     */
    public function gamesAsTeam1()
    {
        return $this->hasMany(Game::class, 'team1_id');
    }

    /**
     * Games where this team is Team 2.
     */
    public function gamesAsTeam2()
    {
        return $this->hasMany(Game::class, 'team2_id');
    }

    /**
     * Games where this team won.
     */
    public function gamesWon()
    {
        return $this->hasMany(Game::class, 'winner_id');
    }

    /**
     * Get all games this team participates in.
     */
    public function getAllGames()
    {
        return Game::where('team1_id', $this->id)
                   ->orWhere('team2_id', $this->id)
                   ->orderBy('round')
                   ->orderBy('match_number');
    }

    /**
     * Get team's record (wins, losses).
     */
    /**
 * Get team's record (wins, losses).
 */
public function getRecord()
{
    // Get all completed games for this team
    $completedGames = Game::where(function($query) {
            $query->where('team1_id', $this->id)
                  ->orWhere('team2_id', $this->id);
        })
        ->where('status', 'completed')
        ->get();
    
    $wins = 0;
    $losses = 0;
    
    foreach ($completedGames as $game) {
        if ($game->winner_id === $this->id) {
            $wins++;
        } else {
            // Only count as loss if there was a winner and it wasn't this team
            if ($game->winner_id !== null) {
                $losses++;
            }
        }
    }

    return [
        'wins' => $wins,
        'losses' => $losses,
        'total' => $completedGames->count(),
    ];
}

    /**
     * Check if team is eliminated from tournament.
     */
    public function isEliminated()
    {
        // In single elimination, team is eliminated if they lost a game
        $lostGames = $this->getAllGames()
                         ->where('status', 'completed')
                         ->where('winner_id', '!=', $this->id)
                         ->where('winner_id', '!=', null)
                         ->count();
        
        return $lostGames > 0;
    }
}