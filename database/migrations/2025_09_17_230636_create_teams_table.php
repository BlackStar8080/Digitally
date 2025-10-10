<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('team_name')->unique();
            $table->string('coach_name')->nullable();
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            
            // Foreign key to sports table
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')
                  ->references('sports_id')
                  ->on('sports')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->foreign('tournament_id')
                  ->references('id')
                  ->on('tournaments')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};