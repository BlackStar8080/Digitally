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
            // Remove old JSON columns (data already migrated to normalized tables)
            $table->dropColumn([
                'team1_selected_players',
                'team2_selected_players',
                'game_data'
            ]);
        });

        echo "\n✅ Old JSON columns removed from games table!\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            // Restore JSON columns if needed
            $table->json('team1_selected_players')->nullable()->after('assistant_referee_2');
            $table->json('team2_selected_players')->nullable()->after('team1_selected_players');
            $table->json('game_data')->nullable()->after('team2_selected_players');
        });

        echo "\n⚠️  JSON columns restored to games table.\n";
    }
};