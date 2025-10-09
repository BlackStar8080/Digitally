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
        Schema::create('game_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->integer('sequence_number')->comment('Order of events in the game');
            $table->enum('team', ['A', 'B', 'GAME'])->comment('A=Team1, B=Team2, GAME=System');
            $table->string('player_number')->nullable()->comment('Jersey number or SYSTEM/TEAM');
            $table->string('action')->comment('2 Points, 3 Points, Foul, etc.');
            $table->integer('points')->default(0);
            $table->string('game_time')->comment('MM:SS format');
            $table->integer('period')->comment('Quarter 1-4');
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            
            // Indexes for performance
            $table->index(['game_id', 'sequence_number']);
            $table->index(['game_id', 'period']);
            $table->index(['game_id', 'team']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_events');
    }
};