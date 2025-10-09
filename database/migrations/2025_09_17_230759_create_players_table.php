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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('sports_id'); // ✅ Changed from sport string to sports_id FK
            $table->integer('number')->nullable();
            $table->string('position')->nullable();
            $table->integer('age')->nullable();

            // Foreign keys
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('cascade');
                  
            $table->foreign('sports_id')
                  ->references('sports_id')
                  ->on('sports')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
};