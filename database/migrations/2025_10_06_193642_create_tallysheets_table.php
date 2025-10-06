<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tallysheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            
            // Team scores
            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);
            
            // Team fouls
            $table->integer('team1_fouls')->default(0);
            $table->integer('team2_fouls')->default(0);
            
            // Team timeouts
            $table->integer('team1_timeouts')->default(0);
            $table->integer('team2_timeouts')->default(0);
            
            // Period scores (stored as JSON)
            $table->json('period_scores')->nullable();
            
            // Running score data (for the checkmarks in tallysheet)
            $table->json('running_scores')->nullable();
            
            // Game events timeline
            $table->json('game_events')->nullable();
            
            // Player foul details
            $table->json('player_fouls')->nullable();
            
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
        Schema::dropIfExists('tallysheets');
    }
};