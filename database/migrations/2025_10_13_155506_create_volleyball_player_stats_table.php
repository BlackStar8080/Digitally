<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('volleyball_player_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('team_id');
            
            // Volleyball-specific stats
            $table->integer('kills')->default(0);
            $table->integer('aces')->default(0);
            $table->integer('blocks')->default(0);
            $table->integer('digs')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('errors')->default(0);
            $table->integer('service_errors')->default(0);
            $table->integer('attack_attempts')->default(0);
            $table->integer('block_assists')->default(0);
            
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
        Schema::dropIfExists('volleyball_player_stats');
    }
};