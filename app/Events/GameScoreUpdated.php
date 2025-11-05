<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $game;
    public $team;
    public $teamAScore;
    public $teamBScore;

    public function __construct(Game $game, $team, $teamAScore, $teamBScore)
    {
        $this->game = $game;
        $this->team = $team;
        $this->teamAScore = $teamAScore;
        $this->teamBScore = $teamBScore;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("game.{$this->game->id}");
    }

    public function broadcastAs()
    {
        return 'score-updated';
    }
}