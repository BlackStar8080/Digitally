<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('player_game_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('team_id');
            
            // Basic stats
            $table->integer('points')->default(0);
            $table->integer('fouls')->default(0);
            
            // Additional stats (for future expansion)
            $table->integer('free_throws_made')->default(0);
            $table->integer('free_throws_attempted')->default(0);
            $table->integer('two_points_made')->default(0);
            $table->integer('two_points_attempted')->default(0);
            $table->integer('three_points_made')->default(0);
            $table->integer('three_points_attempted')->default(0);
            
            // MVP flag
            $table->boolean('is_mvp')->default(false);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            
            // Ensure one stat record per player per game
            $table->unique(['game_id', 'player_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('player_game_stats');
    }
};