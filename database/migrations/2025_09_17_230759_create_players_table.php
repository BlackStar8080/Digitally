<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('cascade');
            
            $table->integer('number')->nullable();
            
            // Foreign key to sports table
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')
                  ->references('sports_id')
                  ->on('sports')
                  ->onDelete('restrict');
            
            $table->string('position')->nullable();
            $table->integer('age')->nullable();
            $table->date('birthday')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('players');
    }
};