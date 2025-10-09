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
        Schema::create('quarter_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->integer('quarter')->comment('1, 2, 3, or 4');
            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            
            // Ensure one record per quarter per game
            $table->unique(['game_id', 'quarter']);
            
            // Index for queries
            $table->index('game_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quarter_scores');
    }
};