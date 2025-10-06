<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bracket_team', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bracket_id');
            $table->unsignedBigInteger('team_id');
            $table->integer('seed')->nullable(); // Tournament seeding (1, 2, 3, etc.)
            $table->timestamps();

            $table->foreign('bracket_id')->references('id')->on('brackets')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            
            // Ensure unique team per bracket
            $table->unique(['bracket_id', 'team_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bracket_team');
    }
};