<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('game.{gameId}', function ($user, $gameId) {
    $game = \App\Models\Game::find($gameId);
    
    if (!$game) {
        return false;
    }

    $hasRole = $game->assignments()
        ->active()
        ->where('user_id', $user->id)
        ->exists();

    return $hasRole ? ['id' => $user->id, 'name' => $user->name] : false;
});