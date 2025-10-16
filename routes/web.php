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

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Public - No Login Required)
|--------------------------------------------------------------------------
*/

// Guest Landing Page - Pure viewer mode, NO login button
Route::get('/', [LandingController::class, 'guestIndex'])->name('guest.landing');

// API for live scores (public)
Route::get('/api/live-scores', [LandingController::class, 'getLiveScores'])->name('api.live-scores');

// Guest tournament view
Route::get('/tournament/{id}/guest', [BracketController::class, 'guestView'])->name('tournament.guest');

/*
|--------------------------------------------------------------------------
| USER/ADMIN ROUTES (With Login Access)
|--------------------------------------------------------------------------
*/

// User Landing Page - WITH login modal button
Route::get('/admin', [LandingController::class, 'userIndex'])->name('user.landing');

// Authentication Routes (Only accessible from /admin page)
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login');

// NO PUBLIC REGISTRATION - Admins create users only
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
// Route::post('/register', [AuthController::class, 'register'])->name('register');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('guest.landing');
    })->name('logout');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    
    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    // Tournament Management
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{id}', [BracketController::class, 'showTournament'])->name('tournaments.show');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::post('/tournaments/{tournament}/assign-teams', [TournamentController::class, 'assignTeams'])->name('tournaments.assign-teams');
    Route::get('/tournament/{id}/bracket/pdf', [TournamentController::class, 'downloadBracketPdf'])->name('tournament.bracket.pdf');
    
    // Bracket Management
    Route::post('/tournaments/{tournament}/brackets', [BracketController::class, 'store'])->name('brackets.store');
    Route::post('/brackets/{bracket}/generate', [BracketController::class, 'generate'])->name('brackets.generate');
    Route::post('/brackets/{bracket}/save-custom', [BracketController::class, 'saveCustomBracket'])->name('brackets.save-custom');
    
    // Team Management
    Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
    Route::get('/teams/{id}', [TeamsController::class, 'show'])->name('teams.show');
    Route::put('/teams/{team}', [TeamsController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamsController::class, 'destroy'])->name('teams.destroy');
    Route::post('/tournaments/{tournament}/teams', [BracketController::class, 'assignTeam'])->name('tournaments.assign-team');
    Route::delete('/tournaments/{tournament}/teams/{team}', [BracketController::class, 'removeTeam'])->name('tournaments.remove-team');
    Route::get('/teams-stats', function() { return 'Teams & Player Stats'; })->name('teams.stats');
    
    // Player Management
    Route::get('/players', [PlayersController::class, 'index'])->name('players.index');
    Route::post('/players', [PlayersController::class, 'store'])->name('players.store');
    Route::get('/stats', [PlayersController::class, 'stats'])->name('players.stats');
    Route::resource('/players', PlayersController::class);
    
    // Game Management
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::patch('/games/{game}', [BracketController::class, 'updateGame'])->name('games.update');
    Route::get('/games/{game}/prepare', [GameController::class, 'prepare'])->name('games.prepare');
    Route::post('/games/{game}/officials', [GameController::class, 'updateOfficials'])->name('games.update-officials');
    Route::post('/games/{game}/start-live', [GameController::class, 'startLive'])->name('games.start-live');
    Route::get('/prepare', [GameController::class, 'index'])->name('games.prepare.index');
    Route::get('/games/{game}/live', [GameController::class, 'live'])->name('games.live');
    Route::get('/games/{game}/tallysheet', [GameController::class, 'tallysheet'])->name('games.tallysheet');
    Route::patch('/games/{game}/update-schedule', [GameController::class, 'updateSchedule'])->name('games.update-schedule');
    Route::post('/games/{game}/complete', [GameController::class, 'completeGame'])->name('games.complete');
    Route::get('/games/{game}/box-score', [GameController::class, 'boxScore'])->name('games.box-score');
    Route::post('/games/{game}/select-mvp', [GameController::class, 'selectMVP'])->name('games.select-mvp');
    
    // Basketball Routes
    Route::get('/games/{game}/basketball-scoresheet', [GameController::class, 'basketballScoresheet'])->name('games.basketball-scoresheet');
    
    // Volleyball Routes
    Route::get('/games/{game}/volleyball-live', [GameController::class, 'volleyballLive'])->name('games.volleyball-live');
    Route::post('/games/{game}/volleyball-complete', [GameController::class, 'completeVolleyballGame'])->name('games.volleyball-complete');
    Route::get('/games/{game}/volleyball-scoresheet', [GameController::class, 'volleyballScoresheet'])->name('games.volleyball-scoresheet');
    Route::get('/games/{game}/volleyball-box-score', [GameController::class, 'volleyballBoxScore'])->name('games.volleyball-box-score');
    Route::post('/games/{game}/select-volleyball-mvp', [GameController::class, 'selectVolleyballMVP'])->name('games.select-volleyball-mvp');
    
    // PDF Routes
    Route::get('/pdf/basketball-scoresheet/{game}/download', [PdfController::class, 'basketballScoresheet'])->name('pdf.basketball.scoresheet.download');
    Route::get('/pdf/basketball-scoresheet/{game}/view', [PdfController::class, 'viewBasketballScoresheet'])->name('pdf.basketball.scoresheet.view');
    Route::get('/games/{gameId}/volleyball-scoresheet-pdf', [PdfController::class, 'viewVolleyballScoresheet'])->name('pdf.volleyball-scoresheet');
});