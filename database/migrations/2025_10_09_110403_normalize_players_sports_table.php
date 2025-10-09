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
        // Check if sport column exists first
        if (!Schema::hasColumn('players', 'sport')) {
            echo "⚠️  'sport' column doesn't exist in players table. Skipping migration.\n";
            return;
        }

        // Step 1: Add sports_id column to players table (nullable for now)
        Schema::table('players', function (Blueprint $table) {
            $table->unsignedBigInteger('sport_id')->nullable()->after('team_id');
        });

        // Step 2: Check if sports table has data
        $sportsCount = DB::table('sports')->count();
        
        if ($sportsCount > 0) {
            // Sports table exists and has data - migrate using it
            $sports = DB::table('sports')->get()->keyBy('name');
            
            foreach ($sports as $sportName => $sport) {
                DB::table('players')
                    ->where('sport', $sportName)
                    ->update(['sport_id' => $sport->id]);
            }
        } else {
            // Sports table is empty - create sports from existing player data
            echo "ℹ️  Sports table is empty. Creating sports from player data...\n";
            
            $uniqueSports = DB::table('players')
                ->select('sport')
                ->whereNotNull('sport')
                ->distinct()
                ->get();
            
            foreach ($uniqueSports as $sportData) {
                // Insert sport and get ID
                $sportId = DB::table('sports')->insertGetId([
                    'name' => $sportData->sport,
                    'type' => 'Team', // Default type
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Update players with this sport
                DB::table('players')
                    ->where('sport', $sportData->sport)
                    ->update(['sport_id' => $sportId]);
            }
            
            echo "✅ Created " . $uniqueSports->count() . " sports from player data.\n";
        }

        // Step 3: Add foreign key constraint
        Schema::table('players', function (Blueprint $table) {
            $table->foreign('sport_id')
                  ->references('id')
                  ->on('sports')
                  ->onDelete('restrict');
        });

        // Step 4: Make sport_id required (only if we have data)
        $playersWithoutSport = DB::table('players')->whereNull('sport_id')->count();
        
        if ($playersWithoutSport === 0) {
            Schema::table('players', function (Blueprint $table) {
                $table->unsignedBigInteger('sport_id')->nullable(false)->change();
            });
        } else {
            echo "⚠️  Warning: {$playersWithoutSport} players still don't have a sport_id. Keeping column nullable.\n";
        }

        // Step 5: Remove the old sport string column
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('sport');
        });

        echo "✅ Player sports normalization complete!\n";
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
            $table->string('sport')->nullable()->after('team_id');
        });

        // Restore sport names from sports table
        $players = DB::table('players')
            ->join('sports', 'players.sport_id', '=', 'sports.id')
            ->select('players.id', 'sports.name as sport_name')
            ->get();

        foreach ($players as $player) {
            DB::table('players')
                ->where('id', $player->id)
                ->update(['sport' => $player->sport_name]);
        }

        // Remove foreign key and sport_id column
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['sport_id']);
            $table->dropColumn('sport_id');
        });

        echo "✅ Rollback complete - restored sport column.\n";
    }
};