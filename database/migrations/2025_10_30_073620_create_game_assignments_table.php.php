<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('game_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('user_id')->nullable(); // For logged-in users
            $table->string('device_token')->nullable(); // For one-time joiners (temporary)
            $table->enum('role', ['scorer', 'stat_keeper']); // Role in the game
            $table->unsignedBigInteger('assigned_by')->nullable(); // Who created this assignment
            $table->timestamp('expires_at')->nullable(); // When device_token expires
            $table->boolean('active')->default(true); // Is this assignment currently active?
            $table->timestamps();

            // Foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['game_id', 'role']);
            $table->index(['game_id', 'active']);
            $table->unique(['game_id', 'user_id', 'role']); // One user can only have one role per game
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_assignments');
    }
};