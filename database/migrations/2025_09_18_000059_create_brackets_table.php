<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('brackets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->string('name'); // e.g., "Main Bracket", "Losers Bracket"
            $table->string('type'); // single-elimination, double-elimination, round-robin
            $table->enum('status', ['setup', 'active', 'completed'])->default('setup');
            $table->json('settings')->nullable(); // Store bracket-specific settings
            $table->timestamps();

            $table->foreign('tournament_id')
                  ->references('id')
                  ->on('tournaments')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('brackets');
    }
};