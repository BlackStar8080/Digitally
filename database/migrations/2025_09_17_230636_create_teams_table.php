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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('team_name')->unique(); // Unique team name
            $table->string('coach_name')->nullable();    // Renamed from coach_name
            $table->string('contact')->nullable();  // Changed from integer to string
            $table->string('address')->nullable();
            $table->string('sport')->nullable();
            $table->unsignedBigInteger('tournament_id')->nullable(); // Added this line
            
            // Add foreign key constraint
            $table->foreign('tournament_id')
                  ->references('id')
                  ->on('tournaments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
};