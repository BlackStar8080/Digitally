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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::post('/admin/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});

Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::post('/games/{game}/complete', [GameController::class, 'completeGame'])->name('games.complete');

// Basketball Scoresheet Routes
Route::get('/games/{game}/basketball-scoresheet', [GameController::class, 'basketballScoresheet'])->name('games.basketball-scoresheet');
Route::get('/pdf/basketball-scoresheet/{game}/download', [PdfController::class, 'basketballScoresheet'])
    ->name('pdf.basketball.scoresheet.download');
Route::get('/pdf/basketball-scoresheet/{game}/view', [PdfController::class, 'viewBasketballScoresheet'])
    ->name('pdf.basketball.scoresheet.view');

// Box Score Routes
Route::get('/games/{game}/box-score', [GameController::class, 'boxScore'])->name('games.box-score');
Route::post('/games/{game}/select-mvp', [GameController::class, 'selectMVP'])->name('games.select-mvp');

// Volleyball MVP Route
Route::post('/games/{game}/select-volleyball-mvp', [GameController::class, 'selectVolleyballMVP'])->name('games.select-volleyball-mvp');

// Tournament PDF
Route::get('/tournament/{id}/bracket/pdf', [TournamentController::class, 'downloadBracketPdf'])
     ->name('tournament.bracket.pdf');

// ✅ UPDATED VOLLEYBALL SCORESHEET ROUTES (Fixed)
// Browser view with download button
Route::get('/games/{game}/volleyball-scoresheet', [GameController::class, 'volleyballScoresheet'])
    ->name('games.volleyball-scoresheet');

// PDF download route (called by download button)
Route::get('/games/{game}/volleyball-scoresheet/download', [PdfController::class, 'volleyballScoresheet'])
    ->name('pdf.volleyball-scoresheet');

// PDF view in browser (if needed - optional)
Route::get('/games/{game}/volleyball-scoresheet/preview', [PdfController::class, 'viewVolleyballScoresheet'])
    ->name('pdf.volleyball-scoresheet.view');

// Tournament Routes
Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
Route::get('/tournaments/{id}', [BracketController::class, 'showTournament'])->name('tournaments.show');
Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');

// Bracket Routes
Route::post('/tournaments/{tournament}/brackets', [BracketController::class, 'store'])->name('brackets.store');
Route::post('/brackets/{bracket}/generate', [BracketController::class, 'generate'])->name('brackets.generate');
Route::post('/brackets/{bracket}/save-custom', [BracketController::class, 'saveCustomBracket'])->name('brackets.save-custom');

// Team Assignment Routes
Route::post('/tournaments/{tournament}/teams', [BracketController::class, 'assignTeam'])->name('tournaments.assign-team');
Route::delete('/tournaments/{tournament}/teams/{team}', [BracketController::class, 'removeTeam'])->name('tournaments.remove-team');
Route::post('/tournaments/{tournament}/assign-teams', [TournamentController::class, 'assignTeams'])->name('tournaments.assign-teams');

// Game Routes
Route::post('/games', [GameController::class, 'store'])->name('games.store');
Route::patch('/games/{game}', [BracketController::class, 'updateGame'])->name('games.update');
Route::get('/games/{game}/prepare', [GameController::class, 'prepare'])->name('games.prepare');
Route::post('/games/{game}/officials', [GameController::class, 'updateOfficials'])->name('games.update-officials');
Route::post('/games/{game}/start-live', [GameController::class, 'startLive'])->name('games.start-live');
Route::get('/prepare', [GameController::class, 'index'])->name('games.prepare.index');
Route::get('/games/{game}/live', [GameController::class, 'live'])->name('games.live');
Route::get('/games/{game}/tallysheet', [GameController::class, 'tallysheet'])->name('games.tallysheet');
Route::patch('/games/{game}/update-schedule', [GameController::class, 'updateSchedule'])->name('games.update-schedule');

// Volleyball Game Routes
Route::get('/games/{game}/volleyball-live', [GameController::class, 'volleyballLive'])->name('games.volleyball-live');
Route::post('/games/{game}/volleyball-complete', [GameController::class, 'completeVolleyballGame'])->name('games.volleyball-complete');
Route::get('/games/{game}/volleyball-box-score', [GameController::class, 'volleyballBoxScore'])->name('games.volleyball-box-score');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Player Routes
Route::get('/players', [PlayersController::class, 'index'])->name('players.index');
Route::post('/players', [PlayersController::class, 'store'])->name('players.store');
Route::get('/stats', [PlayersController::class, 'stats'])->name('players.stats');
Route::resource('/players', PlayersController::class);

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// API route for live scores
Route::get('/api/live-scores', [LandingController::class, 'getLiveScores'])->name('api.live-scores');

// Guest tournament view
Route::get('/tournament/{id}/guest', [BracketController::class, 'guestView'])->name('tournament.guest');

// Team Routes
Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
Route::get('/teams/{id}', [TeamsController::class, 'show'])->name('teams.show');
Route::put('/teams/{team}', [TeamsController::class, 'update'])->name('teams.update');
Route::delete('/teams/{team}', [TeamsController::class, 'destroy'])->name('teams.destroy');

// Teams Stats
Route::get('/teams-stats', function() { return 'Teams & Player Stats'; })->name('teams.stats');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Activity Logs
use App\Http\Controllers\ActivityLogController;
Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

Route::get('/check-player', [App\Http\Controllers\PlayersController::class, 'checkPlayer'])->name('check.player');
