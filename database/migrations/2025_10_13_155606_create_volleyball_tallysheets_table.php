<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('volleyball_tallysheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            
            // Set scores (who won each set)
            $table->integer('team1_sets_won')->default(0);
            $table->integer('team2_sets_won')->default(0);
            
            // Individual set scores
            $table->json('set_scores')->nullable(); // [{set: 1, team1: 25, team2: 20}, ...]
            
            // Team stats
            $table->integer('team1_timeouts')->default(0);
            $table->integer('team2_timeouts')->default(0);
            $table->integer('team1_substitutions')->default(0);
            $table->integer('team2_substitutions')->default(0);
            
            // Serving tracking
            $table->string('initial_server')->nullable(); // 'A' or 'B'
            $table->json('serving_order')->nullable();
            
            // Game events timeline
            $table->json('game_events')->nullable();
            
            // Running scores per set
            $table->json('running_scores')->nullable();
            
            // Best player / MVP info
            $table->unsignedBigInteger('best_player_id')->nullable();
            $table->json('best_player_stats')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('best_player_id')->references('id')->on('players')->onDelete('set null');
            
            // Ensure one tallysheet per game
            $table->unique('game_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('volleyball_tallysheets');
    }
};