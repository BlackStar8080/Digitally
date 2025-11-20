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
     * ✅ FIXED: Join as stat-keeper using token - Always redirect to landing page for login
     * GET /games/{game}/join?token=xxx
     */
    /**
 * ✅ FIXED: Join as stat-keeper using token - Redirect to waiting lobby
 * GET /games/{game}/join?token=xxx
 */
public function join(Request $request, Game $game)
{
    $token = $request->query('token');

    if (!$token) {
        return redirect()->route('landing')->with('error', 'No join token provided');
    }

    // Find valid assignment with this token
    $assignment = $game->assignments()
        ->statKeepers()
        ->where('device_token', $token)
        ->first();

    if (!$assignment || !$assignment->isValid()) {
        return redirect()->route('landing')->with('error', 'Invalid or expired join token. Please ask the scorer for a new invite link.');
    }

    // ✅ ALWAYS REQUIRE LOGIN - Check if user is logged in
    if (!auth()->check()) {
        // Store token and game info in session for after login
        session([
            'pending_game_join' => [
                'game_id' => $game->id,
                'token' => $token,
                'role' => 'stat_keeper',
                'game_name' => "{$game->team1->team_name} vs {$game->team2->team_name}",
            ]
        ]);

        // Redirect to landing page with message
        return redirect()->route('landing')->with('join_prompt', 'Please log in or register to join as Stat-Keeper for this game');
    }

    // ✅ User is logged in - Check if they're already the scorer
    $isAlreadyScorer = $game->assignments()
        ->active()
        ->scorers()
        ->where('user_id', auth()->id())
        ->exists();

    if ($isAlreadyScorer) {
        return redirect()->route('landing')->with('error', 'You are already the Scorer for this game. Please log out and use a different account to join as Stat-Keeper.');
    }

    // ✅ Check if user already joined as stat-keeper
    $alreadyJoined = $game->assignments()
        ->active()
        ->statKeepers()
        ->where('user_id', auth()->id())
        ->exists();

    if ($alreadyJoined) {
        // Clear pending join session
        session()->forget('pending_game_join');
        
        // ✅ NEW: Check if game has started
        if ($game->status === 'in_progress') {
            // Game already started, go directly to live
            return redirect()->route('games.live', [
                'game' => $game->id,
                'role' => 'stat_keeper'
            ])->with('success', 'Welcome back! Joining game...');
        } else {
            // Game not started yet, go to waiting lobby
            return redirect()->route('games.waiting-lobby', $game)
                ->with('success', 'Welcome back! Waiting for scorer to start...');
        }
    }

    // ✅ ASSIGN USER AS STAT-KEEPER
    if ($assignment->user_id === null) {
        $assignment->update([
            'user_id' => auth()->id(),
            'device_token' => null, // Clear device token once used
        ]);
        
        \Log::info("User " . auth()->id() . " joined game {$game->id} as stat_keeper");
    }

    // Clear pending join session
    session()->forget('pending_game_join');

    // ✅ NEW: Redirect to waiting lobby instead of directly to live
    return redirect()->route('games.waiting-lobby', $game)
        ->with('success', 'You joined as Stat-keeper! Waiting for scorer to start the game...');
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
     * ✅ FIXED: Get user's role in game - prioritize stat_keeper if joining via link
     */
    public static function getUserRole($user, Game $game)
    {
        // Check if user has stat_keeper role first (prioritize this)
        $statKeeperAssignment = $game->assignments()
            ->active()
            ->statKeepers()
            ->where('user_id', $user->id)
            ->first();

        if ($statKeeperAssignment) {
            return 'stat_keeper';
        }

        // Then check for scorer role
        $scorerAssignment = $game->assignments()
            ->active()
            ->scorers()
            ->where('user_id', $user->id)
            ->first();

        if ($scorerAssignment) {
            return 'scorer';
        }

        // Default to null if no role found
        return null;
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

    /**
 * Check if game has started (for stat-keeper polling)
 * GET /games/{game}/check-start-status
 */
public function checkStartStatus(Game $game)
{
    // Check if user has stat_keeper role
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    $isStatKeeper = $game->assignments()
        ->active()
        ->statKeepers()
        ->where('user_id', auth()->id())
        ->exists();

    if (!$isStatKeeper) {
        return response()->json(['error' => 'Not authorized'], 403);
    }

    return response()->json([
        'game_started' => $game->status === 'in_progress',
        'game_status' => $game->status,
    ]);
}

/**
 * Show waiting lobby for stat-keeper
 * GET /games/{game}/waiting-lobby
 */
public function waitingLobby(Game $game)
{
    // Verify user is authenticated
    if (!auth()->check()) {
        return redirect()->route('landing')->with('error', 'Please log in first');
    }

    // Verify user is assigned as stat-keeper
    $isStatKeeper = $game->assignments()
        ->active()
        ->statKeepers()
        ->where('user_id', auth()->id())
        ->exists();

    if (!$isStatKeeper) {
        return redirect()->route('dashboard')->with('error', 'You are not assigned to this game');
    }

    // If game already started, redirect to live
    if ($game->status === 'in_progress') {
        return redirect()->route('games.live', [
            'game' => $game->id,
            'role' => 'stat_keeper'
        ]);
    }

    // Load game relationships
    $game->load(['team1', 'team2']);

    return view('games.waiting-lobby', compact('game'));
}
}