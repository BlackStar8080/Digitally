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
        Schema::table('games', function (Blueprint $table) {
            // Add new columns for team-level stats (extracted from game_data JSON)
            $table->integer('team1_fouls')->default(0)->after('team2_score');
            $table->integer('team2_fouls')->default(0)->after('team1_fouls');
            $table->integer('team1_timeouts')->default(0)->after('team2_fouls');
            $table->integer('team2_timeouts')->default(0)->after('team1_timeouts');
            $table->integer('total_quarters')->default(4)->after('team2_timeouts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn([
                'team1_fouls',
                'team2_fouls',
                'team1_timeouts',
                'team2_timeouts',
                'total_quarters'
            ]);
        });
    }
};