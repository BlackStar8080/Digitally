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
        // Step 1: Add sports_id column to players table
        Schema::table('players', function (Blueprint $table) {
            $table->unsignedBigInteger('sports_id')->nullable()->after('sport');
            
            $table->foreign('sports_id')
                  ->references('sports_id')
                  ->on('sports')
                  ->onDelete('restrict');
        });

        // Step 2: Update existing players with correct sports_id
        $sports = DB::table('sports')->get()->keyBy('sports_name');
        
        foreach ($sports as $sportName => $sport) {
            DB::table('players')
                ->where('sport', $sportName)
                ->update(['sports_id' => $sport->sports_id]);
        }

        // Step 3: Make sports_id required (after data migration)
        Schema::table('players', function (Blueprint $table) {
            $table->unsignedBigInteger('sports_id')->nullable(false)->change();
        });

        // Step 4: Remove the old sport string column
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('sport');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back sport column
        Schema::table('players', function (Blueprint $table) {
            $table->string('sport')->nullable()->after('sports_id');
        });

        // Restore sport names from sports table
        $players = DB::table('players')
            ->join('sports', 'players.sports_id', '=', 'sports.sports_id')
            ->select('players.id', 'sports.sports_name')
            ->get();

        foreach ($players as $player) {
            DB::table('players')
                ->where('id', $player->id)
                ->update(['sport' => $player->sports_name]);
        }

        // Remove foreign key and sports_id column
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['sports_id']);
            $table->dropColumn('sports_id');
        });
    }
};