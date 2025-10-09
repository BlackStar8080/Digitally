<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all games that have data in JSON format
        $games = DB::table('games')->whereNotNull('game_data')->get();

        foreach ($games as $game) {
            // Migrate team1_selected_players to game_rosters
            $this->migratePlayerRoster($game, 'team1_selected_players', $game->team1_id);
            
            // Migrate team2_selected_players to game_rosters
            $this->migratePlayerRoster($game, 'team2_selected_players', $game->team2_id);
            
            // Migrate game_data to new columns and tables
            $this->migrateGameData($game);
        }

        echo "\n✅ Data migration completed! Migrated " . $games->count() . " games.\n";
    }

    /**
     * Migrate player roster from JSON to game_rosters table
     */
    private function migratePlayerRoster($game, $jsonColumn, $teamId)
    {
        $playerData = json_decode($game->$jsonColumn, true);
        
        if (!$playerData || !isset($playerData['roster'])) {
            return;
        }

        $roster = $playerData['roster'] ?? [];
        $starters = $playerData['starters'] ?? [];

        foreach ($roster as $index => $playerId) {
            // Check if player exists
            $playerExists = DB::table('players')->where('id', $playerId)->exists();
            
            if (!$playerExists) {
                echo "⚠️  Warning: Player ID {$playerId} not found, skipping...\n";
                continue;
            }

            $isStarter = in_array($playerId, $starters);
            $positionNumber = null;
            
            if ($isStarter) {
                $positionNumber = array_search($playerId, $starters) + 1;
            }

            // Insert into game_rosters table
            DB::table('game_rosters')->insertOrIgnore([
                'game_id' => $game->id,
                'player_id' => $playerId,
                'team_id' => $teamId,
                'is_starter' => $isStarter,
                'position_number' => $positionNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Migrate game_data JSON to normalized tables
     */
    private function migrateGameData($game)
    {
        $gameData = json_decode($game->game_data, true);
        
        if (!$gameData) {
            return;
        }

        // Update team stats in games table
        DB::table('games')->where('id', $game->id)->update([
            'team1_fouls' => $gameData['team1_fouls'] ?? 0,
            'team2_fouls' => $gameData['team2_fouls'] ?? 0,
            'team1_timeouts' => $gameData['team1_timeouts'] ?? 0,
            'team2_timeouts' => $gameData['team2_timeouts'] ?? 0,
            'total_quarters' => $gameData['total_quarters'] ?? 4,
        ]);

        // Migrate game_events array to game_events table
        if (isset($gameData['game_events']) && is_array($gameData['game_events'])) {
            foreach ($gameData['game_events'] as $event) {
                // Extract period number from string like "Q1", "Q2"
                $period = 1;
                if (isset($event['period'])) {
                    $period = (int) filter_var($event['period'], FILTER_SANITIZE_NUMBER_INT);
                }

                DB::table('game_events')->insert([
                    'game_id' => $game->id,
                    'sequence_number' => $event['id'] ?? 0,
                    'team' => $event['team'] ?? 'GAME',
                    'player_number' => $event['player'] ?? null,
                    'action' => $event['action'] ?? 'Unknown',
                    'points' => $event['points'] ?? 0,
                    'game_time' => $event['time'] ?? '00:00',
                    'period' => $period,
                    'occurred_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate period_scores to quarter_scores table
        if (isset($gameData['period_scores'])) {
            $periodScores = $gameData['period_scores'];
            
            for ($quarter = 1; $quarter <= 4; $quarter++) {
                $team1Score = $periodScores['team1'][$quarter - 1] ?? 0;
                $team2Score = $periodScores['team2'][$quarter - 1] ?? 0;

                DB::table('quarter_scores')->insertOrIgnore([
                    'game_id' => $game->id,
                    'quarter' => $quarter,
                    'team1_score' => $team1Score,
                    'team2_score' => $team2Score,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Clear migrated data from new tables
        DB::table('game_rosters')->truncate();
        DB::table('game_events')->truncate();
        DB::table('quarter_scores')->truncate();
        
        // Reset new columns in games table
        DB::table('games')->update([
            'team1_fouls' => 0,
            'team2_fouls' => 0,
            'team1_timeouts' => 0,
            'team2_timeouts' => 0,
            'total_quarters' => 4,
        ]);
    }
};