<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('division');
            
            // Foreign key to sports table
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')
                  ->references('sports_id')
                  ->on('sports')
                  ->onDelete('restrict');
            

            $table->date('start_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};