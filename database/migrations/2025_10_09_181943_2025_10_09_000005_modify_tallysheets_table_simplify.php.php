<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tallysheets', function (Blueprint $table) {
            // Remove columns that are now normalized in other tables
            $columnsToDrop = [];
            
            // Check each column exists before dropping
            if (Schema::hasColumn('tallysheets', 'team1_score')) {
                $columnsToDrop[] = 'team1_score';
            }
            if (Schema::hasColumn('tallysheets', 'team2_score')) {
                $columnsToDrop[] = 'team2_score';
            }
            if (Schema::hasColumn('tallysheets', 'team1_fouls')) {
                $columnsToDrop[] = 'team1_fouls';
            }
            if (Schema::hasColumn('tallysheets', 'team2_fouls')) {
                $columnsToDrop[] = 'team2_fouls';
            }
            if (Schema::hasColumn('tallysheets', 'team1_timeouts')) {
                $columnsToDrop[] = 'team1_timeouts';
            }
            if (Schema::hasColumn('tallysheets', 'team2_timeouts')) {
                $columnsToDrop[] = 'team2_timeouts';
            }
            if (Schema::hasColumn('tallysheets', 'period_scores')) {
                $columnsToDrop[] = 'period_scores';
            }
            if (Schema::hasColumn('tallysheets', 'running_scores')) {
                $columnsToDrop[] = 'running_scores';
            }
            if (Schema::hasColumn('tallysheets', 'game_events')) {
                $columnsToDrop[] = 'game_events';
            }
            if (Schema::hasColumn('tallysheets', 'player_fouls')) {
                $columnsToDrop[] = 'player_fouls';
            }

            // Drop all at once
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        echo "\n✅ Tallysheet table simplified! Removed redundant columns.\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tallysheets', function (Blueprint $table) {
            // Restore removed columns
            $table->integer('team1_score')->default(0)->after('game_id');
            $table->integer('team2_score')->default(0)->after('team1_score');
            $table->integer('team1_fouls')->default(0)->after('team2_score');
            $table->integer('team2_fouls')->default(0)->after('team1_fouls');
            $table->integer('team1_timeouts')->default(0)->after('team2_fouls');
            $table->integer('team2_timeouts')->default(0)->after('team1_timeouts');
            $table->json('period_scores')->nullable()->after('team2_timeouts');
            $table->json('running_scores')->nullable()->after('period_scores');
            $table->json('game_events')->nullable()->after('running_scores');
            $table->json('player_fouls')->nullable()->after('game_events');
        });
    }
};