<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\GameAssignmentController;

class GameRoleMiddleware
{
    public function handle($request, Closure $next, $requiredRole)
    {
        $game = $request->route('game');

        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $userRole = GameAssignmentController::getUserRole(auth()->user(), $game);

        if ($userRole !== $requiredRole) {
            return response()->json([
                'error' => "This action requires $requiredRole role. You are: $userRole",
            ], 403);
        }

        return $next($request);
    }
}