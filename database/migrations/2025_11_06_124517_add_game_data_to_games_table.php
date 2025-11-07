<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            // Add game_data if it doesn't exist
            if (!Schema::hasColumn('games', 'game_data')) {
                $table->json('game_data')->nullable()->after('completed_at');
            }
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('game_data');
        });
    }
};