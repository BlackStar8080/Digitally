<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bracket_id');
            $table->integer('round'); // 1, 2, 3, etc.
            $table->integer('match_number'); // Position in that round
            $table->unsignedBigInteger('team1_id')->nullable();
            $table->unsignedBigInteger('team2_id')->nullable();
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->integer('team1_score')->nullable();
            $table->integer('team2_score')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->json('game_details')->nullable(); // Store game-specific data (sets, quarters, etc.)
            $table->timestamps();

            // Foreign keys
            $table->foreign('bracket_id')->references('id')->on('brackets')->onDelete('cascade');
            $table->foreign('team1_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('team2_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('winner_id')->references('id')->on('teams')->onDelete('set null');

            // Indexes for performance
            $table->index(['bracket_id', 'round']);
            $table->index(['bracket_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
};