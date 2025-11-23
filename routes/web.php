<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\BracketController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GameAssignmentController;
use App\Http\Controllers\ScorekeeperController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Guest Login - MUST be outside auth middleware
Route::post('/guest-login', [AuthController::class, 'guestLogin'])->name('guest.login');
Route::get('/games/{game}/join', [GameAssignmentController::class, 'join'])->name('games.join');


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/admin/register', [AuthController::class, 'register'])->name('admin.register');


Route::post('/logout', function () {
    Auth::logout();
    
    // ✅ Clear guest session on logout
    session()->forget('is_guest');
    session()->forget('guest_name');
    session()->flush();
    
    // Flash a success message so the global toast in the layout can show it
    return redirect('/')->with('success', 'You have been logged out successfully.');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Routes accessible to BOTH guests and authenticated users
| ✅ ADDED: auth middleware to require login/guest login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.or.guest'])->group(function () { // ✅ Changed from 'auth' to 'auth.or.guest'
    // Dashboard - accessible to guests and logged-in users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Games (view only)
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/{game}/box-score', [GameController::class, 'boxScore'])->name('games.box-score');
    Route::get('/games/{game}/volleyball-box-score', [GameController::class, 'volleyballBoxScore'])->name('games.volleyball-box-score');

    // Teams (view only)
    Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
    Route::get('/teams/{id}', [TeamsController::class, 'show'])->name('teams.show');

    // Players (view only)
    Route::get('/players', [PlayersController::class, 'index'])->name('players.index');
    Route::get('/stats', [PlayersController::class, 'stats'])->name('players.stats');
    Route::get('/check-player', [PlayersController::class, 'checkPlayer'])->name('check.player');

    // Tournaments (view only)
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/{id}', [BracketController::class, 'showTournament'])->name('tournaments.show');
    Route::get('/tournament/{id}/guest', [BracketController::class, 'guestView'])->name('tournament.guest');

    // API route for live scores
    Route::get('/api/live-scores', [LandingController::class, 'getLiveScores'])->name('api.live-scores');

});

/*
|--------------------------------------------------------------------------
| Guest-Restricted Routes (Only for logged-in users, NOT guests)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.or.guest', 'guest.restrict'])->group(function () {
    
    // Reports (completely blocked for guests)
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    // Tallysheet routes (blocked for guests)
    Route::get('/games/{game}/tallysheet', [GameController::class, 'tallysheet'])->name('games.tallysheet');
    Route::get('/games/{game}/basketball-scoresheet', [GameController::class, 'basketballScoresheet'])->name('games.basketball-scoresheet');
    Route::get('/games/{game}/volleyball-scoresheet', [GameController::class, 'volleyballScoresheet'])->name('games.volleyball-scoresheet');
    
    // PDF Routes
    Route::get('/pdf/basketball-scoresheet/{game}/download', [PdfController::class, 'basketballScoresheet'])->name('pdf.basketball.scoresheet.download');
    Route::get('/pdf/basketball-scoresheet/{game}/view', [PdfController::class, 'viewBasketballScoresheet'])->name('pdf.basketball.scoresheet.view');
    Route::get('/games/{game}/volleyball-scoresheet/download', [PdfController::class, 'volleyballScoresheet'])->name('pdf.volleyball-scoresheet');
    Route::get('/games/{game}/volleyball-scoresheet/preview', [PdfController::class, 'viewVolleyballScoresheet'])->name('pdf.volleyball-scoresheet.view');
    Route::get('/tournament/{id}/bracket/pdf', [TournamentController::class, 'downloadBracketPdf'])->name('tournament.bracket.pdf');
    Route::get('/tournaments/{tournament}/bracket/pdf', [PdfController::class, 'downloadBracketPdf'])->name('tournaments.bracket.pdf');
    
    // Game Management (create/edit/delete)
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::post('/games/{game}/complete', [GameController::class, 'completeGame'])->name('games.complete');
    Route::patch('/games/{game}', [BracketController::class, 'updateGame'])->name('games.update');
    Route::get('/games/{game}/prepare', [GameController::class, 'prepare'])->name('games.prepare');
    Route::post('/games/{game}/officials', [GameController::class, 'updateOfficials'])->name('games.update-officials');
    Route::post('/games/{game}/start-live', [GameController::class, 'startLive'])->name('games.start-live');
    Route::get('/prepare', [GameController::class, 'index'])->name('games.prepare.index');
    
    // ✅ SPECIFIC GAME ROUTES - MUST BE BEFORE /games/{game}/live
    Route::post('/games/{game}/generate-invite', [GameAssignmentController::class, 'generateInvite'])->name('games.generate-invite');
    Route::get('/games/{game}/invite', [GameAssignmentController::class, 'showInvite'])->name('games.invite');
    Route::get('/games/{game}/connected-users', [GameAssignmentController::class, 'getConnectedUsers'])->name('games.connected-users');
    
    // ✅ GENERAL GAME ROUTES - AFTER SPECIFIC ROUTES
    Route::get('/games/{game}/live', [GameController::class, 'live'])->name('games.live');
    // Add these routes with your other game routes
Route::post('/games/{game}/auto-save', [GameController::class, 'autoSave'])->name('games.auto-save');
Route::get('/games/{game}/load-state', [GameController::class, 'loadState'])->name('games.load-state');
    Route::patch('/games/{game}/update-schedule', [GameController::class, 'updateSchedule'])->name('games.update-schedule');
    Route::get('/games/{game}/volleyball-live', [GameController::class, 'volleyballLive'])->name('games.volleyball-live');
    Route::post('/games/{game}/volleyball-complete', [GameController::class, 'completeVolleyballGame'])->name('games.volleyball-complete');
    Route::post('/games/{game}/select-mvp', [GameController::class, 'selectMVP'])->name('games.select-mvp');
    Route::post('/games/{game}/select-volleyball-mvp', [GameController::class, 'selectVolleyballMVP'])->name('games.select-volleyball-mvp');
    
    // Team Management (create/edit/delete)
    Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
    Route::put('/teams/{team}', [TeamsController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamsController::class, 'destroy'])->name('teams.destroy');
    Route::get('/teams-stats', function() { return 'Teams & Player Stats'; })->name('teams.stats');
    
    // Player Management (create/edit/delete)
    Route::post('/players', [PlayersController::class, 'store'])->name('players.store');
    Route::put('/players/{player}', [PlayersController::class, 'update'])->name('players.update');
    Route::delete('/players/{player}', [PlayersController::class, 'destroy'])->name('players.destroy');
    Route::resource('/players', PlayersController::class)->except(['index', 'show']);

    // Scorekeeper Management (blocked for guests)
Route::get('/scorekeepers', [ScorekeeperController::class, 'index'])->name('scorekeepers.index');
Route::post('/scorekeepers', [ScorekeeperController::class, 'store'])->name('scorekeepers.store');
Route::put('/scorekeepers/{scorekeeper}', [ScorekeeperController::class, 'update'])->name('scorekeepers.update');
Route::delete('/scorekeepers/{scorekeeper}', [ScorekeeperController::class, 'destroy'])->name('scorekeepers.destroy');
    
    // Tournament Management (create/edit/delete)
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::post('/tournaments/{tournament}/brackets', [BracketController::class, 'store'])->name('brackets.store');
    Route::post('/brackets/{bracket}/generate', [BracketController::class, 'generate'])->name('brackets.generate');
    Route::post('/brackets/{bracket}/save-custom', [BracketController::class, 'saveCustomBracket'])->name('brackets.save-custom');
    Route::post('/tournaments/{tournament}/teams', [BracketController::class, 'assignTeam'])->name('tournaments.assign-team');
    Route::delete('/tournaments/{tournament}/teams/{team}', [BracketController::class, 'removeTeam'])->name('tournaments.remove-team');
    Route::post('/tournaments/{tournament}/assign-teams', [TournamentController::class, 'assignTeams'])->name('tournaments.assign-teams');
   // Inside the guest-restricted middleware group (around line 80)
    Route::get('/tournaments/{tournament}/info/preview', [PdfController::class, 'previewTournamentInfo'])->name('tournaments.info.preview');
    Route::get('/tournaments/{tournament}/info/download', [PdfController::class, 'downloadTournamentInfo'])->name('tournaments.info.download');

    // ✅ NEW: Waiting lobby for stat-keeper
    Route::get('/games/{game}/waiting-lobby', [GameAssignmentController::class, 'waitingLobby'])
        ->name('games.waiting-lobby');

    Route::get('/games/{game}/check-start-status', [GameAssignmentController::class, 'checkStartStatus'])
    ->name('games.check-start-status');
});

// ✅ API Routes - With Role-Based Permission Checks
Route::middleware(['auth'])->group(function () {
    
    // ============ SCORER ONLY - Scoring Actions ============
    Route::post('/api/record-score/{game}', [GameController::class, 'recordScore'])
        ->middleware('game.role:record-score');
    
    Route::post('/api/record-foul/{game}', [GameController::class, 'recordFoul'])
        ->middleware('game.role:record-foul');
    
    Route::post('/api/record-timeout/{game}', [GameController::class, 'recordTimeout'])
        ->middleware('game.role:record-timeout');
    
    Route::post('/api/record-substitution/{game}', [GameController::class, 'recordSubstitution'])
        ->middleware('game.role:record-substitution');
    
    // ============ STAT-KEEPER ONLY - Stat Recording ============
    Route::post('/api/record-stat/{game}', [GameController::class, 'recordStat'])
        ->middleware('game.role:record-stat');
    
    Route::post('/api/record-assist/{game}', [GameController::class, 'recordAssist'])
        ->middleware('game.role:record-assist');
    
    Route::post('/api/record-steal/{game}', [GameController::class, 'recordSteal'])
        ->middleware('game.role:record-steal');
    
    Route::post('/api/record-rebound/{game}', [GameController::class, 'recordRebound'])
        ->middleware('game.role:record-rebound');
    
    Route::post('/api/record-block/{game}', [GameController::class, 'recordBlock'])
        ->middleware('game.role:record-block');
    
    // ============ BOTH - View Only ============
    Route::get('/api/game-state/{game}', [GameController::class, 'getGameState'])
        ->middleware('game.role:get-game-state');
    
    Route::post('/api/update-game-state/{game}', [GameController::class, 'updateGameState'])
        ->middleware('game.role:record-score'); // Only scorer can update state
    
    Route::get('/api/connected-users/{game}', [GameAssignmentController::class, 'getConnectedUsers'])
        ->middleware('game.role:get-connected-users');
});