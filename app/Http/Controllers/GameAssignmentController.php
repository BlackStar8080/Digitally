<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameAssignmentController extends Controller
{
    /**
     * Auto-assign first user as scorer when viewing prepare page
     * Called from GameController::prepare()
     */
    public function ensureScorer(Game $game, $user)
    {
        // Check if game already has a scorer
        $existingScorer = $game->assignments()
            ->active()
            ->scorers()
            ->first();

        if (!$existingScorer) {
            // Assign this user as scorer
            GameAssignment::create([
                'game_id' => $game->id,
                'user_id' => $user->id,
                'role' => 'scorer',
                'assigned_by' => $user->id,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Generate invite link/token for stat-keeper
     * POST /games/{game}/generate-invite
     */
    public function generateInvite(Request $request, Game $game)
    {
        // Verify caller is the scorer
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $canGenerateInvite = $game->assignments()
            ->active()
            ->scorers()
            ->where('user_id', auth()->id())
            ->exists();

        if (!$canGenerateInvite) {
            return response()->json(['error' => 'Only the scorer can generate invites'], 403);
        }

        // Check if already has pending invite
        $existingInvite = $game->assignments()
            ->statKeepers()
            ->whereNull('user_id')
            ->first();

        if ($existingInvite && $existingInvite->isValid()) {
            $token = $existingInvite->device_token;
        } else {
            // Deactivate old invite if expired
            if ($existingInvite) {
                $existingInvite->deactivate();
            }

            // Create new device token
            $token = Str::random(32);

            GameAssignment::create([
                'game_id' => $game->id,
                'device_token' => $token,
                'role' => 'stat_keeper',
                'assigned_by' => auth()->id(),
                'expires_at' => now()->addHours(4), // Token valid for 4 hours
            ]);
        }

        // Generate join URL
        $joinUrl = route('games.join', [
            'game' => $game->id,
            'token' => $token,
        ]);

        return response()->json([
            'success' => true,
            'token' => $token,
            'join_url' => $joinUrl,
            'expires_at' => now()->addHours(4)->toIso8601String(),
        ]);
    }

    /**
     * Join as stat-keeper using token
     * GET /games/{game}/join?token=xxx
     */
    public function join(Request $request, Game $game)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->back()->with('error', 'No join token provided');
        }

        // Find valid assignment with this token
        $assignment = $game->assignments()
            ->statKeepers()
            ->where('device_token', $token)
            ->first();

        if (!$assignment || !$assignment->isValid()) {
            return redirect()->back()->with('error', 'Invalid or expired join token');
        }

        // If user is logged in, link this assignment to their user_id
        if (auth()->check()) {
            // ✅ CHECK IF USER ALREADY JOINED
            $alreadyJoined = $game->assignments()
                ->statKeepers()
                ->where('user_id', auth()->id())
                ->first();

            if ($alreadyJoined) {
                // User already joined, just go to live
                return redirect()->route('games.live', [
                'game' => $game->id,
                'role' => 'stat_keeper'  // ✅ PASS ROLE EXPLICITLY
            ])->with('success', 'You joined as Stat-keeper');
            }

            // ✅ ONLY UPDATE IF THIS IS A DEVICE-TOKEN ASSIGNMENT
            if ($assignment->user_id === null) {
                $assignment->update([
                    'user_id' => auth()->id(),
                    'device_token' => null, // Clear device token once used
                ]);
            }

            return redirect()->route('games.live', [
                'game' => $game->id,
                'role' => 'stat_keeper'  // ✅ PASS ROLE EXPLICITLY
            ])->with('success', 'You joined as Stat-keeper');
        }

        // If not logged in, redirect to login with token in query
        return redirect()->route('login.form')
            ->with('message', 'Please log in to join the game')
            ->with('join_token', $token)
            ->with('game_id', $game->id);
    }

    /**
     * Get all connected users for a game
     * GET /games/{game}/connected-users
     */
    public function getConnectedUsers(Game $game)
    {
        $assignments = $game->assignments()
            ->active()
            ->with('user')
            ->get();

        $connected = $assignments->map(function ($assignment) {
            return [
                'user_id' => $assignment->user_id,
                'user_name' => $assignment->user?->name ?? 'Anonymous',
                'role' => $assignment->role,
                'connected_at' => $assignment->updated_at->toIso8601String(),
            ];
        });

        return response()->json([
            'connected_users' => $connected,
            'total' => $connected->count(),
        ]);
    }

    /**
     * Check if user can perform action based on role
     */
    public static function canScore($user, Game $game)
    {
        return $game->assignments()
            ->active()
            ->scorers()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Check if user can record stats
     */
    public static function canRecordStats($user, Game $game)
    {
        return $game->assignments()
            ->active()
            ->statKeepers()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Check if user has any active role in game
     */
    public static function hasActiveRole($user, Game $game)
    {
        return $game->assignments()
            ->active()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get user's role in game
     */
    public static function getUserRole($user, Game $game)
    {
        $assignment = $game->assignments()
            ->active()
            ->where('user_id', $user->id)
            ->first();

        return $assignment?->role;
    }

public function showInvite(Game $game)
{
    // Check if user is the scorer
    if (!auth()->check()) {
        return redirect()->route('login.form');
    }

    $isScorer = $game->assignments()
        ->active()
        ->scorers()
        ->where('user_id', auth()->id())
        ->exists();

    if (!$isScorer) {
        return redirect()->back()->with('error', 'Only the scorer can generate invites');
    }

    return view('games.invite', compact('game'));
}
}