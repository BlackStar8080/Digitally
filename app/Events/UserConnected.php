<?php

namespace App\Events;

use App\Models\Game;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserConnected implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $game;
    public $user;
    public $role;

    public function __construct(Game $game, User $user, $role)
    {
        $this->game = $game;
        $this->user = $user;
        $this->role = $role;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("game.{$this->game->id}");
    }

    public function broadcastAs()
    {
        return 'user-connected';
    }
}