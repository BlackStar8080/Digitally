<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\GameAssignmentController;

class GameRolePermission
{
    /**
     * Permissions matrix for game actions
     * Format: action => [allowed_roles]
     */
    private $permissions = [
        // Scoring actions - only scorer
        'record-score' => ['scorer'],
        'record-foul' => ['scorer'],
        'record-timeout' => ['scorer'],
        'record-substitution' => ['scorer'],
        
        // Stat actions - only stat-keeper
        'record-stat' => ['stat_keeper'],
        'record-assist' => ['stat_keeper'],
        'record-steal' => ['stat_keeper'],
        'record-rebound' => ['stat_keeper'],
        'record-block' => ['stat_keeper'],
        
        // View actions - both can view
        'get-game-state' => ['scorer', 'stat_keeper'],
        'get-connected-users' => ['scorer', 'stat_keeper'],
    ];

    public function handle($request, Closure $next, $action)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Get the game from route parameters
        $game = $request->route('game');
        if (!$game) {
            return response()->json(['error' => 'Game not found'], 404);
        }

        // Get user's role in this game
        $userRole = GameAssignmentController::getUserRole(auth()->user(), $game);

        if (!$userRole) {
            return response()->json(['error' => 'You do not have access to this game'], 403);
        }

        // Check if user's role is allowed for this action
        $allowedRoles = $this->permissions[$action] ?? [];

        if (!in_array($userRole, $allowedRoles)) {
            return response()->json([
                'error' => "Action '$action' is not allowed for role '$userRole'",
                'allowed_roles' => $allowedRoles,
                'your_role' => $userRole
            ], 403);
        }

        // Store the user role in the request for use in the controller
        $request->merge(['user_role' => $userRole]);

        return $next($request);
    }
}