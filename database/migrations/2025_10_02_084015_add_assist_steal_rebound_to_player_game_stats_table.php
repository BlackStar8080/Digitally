<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('player_game_stats', function (Blueprint $table) {
            $table->integer('assists')->default(0)->after('three_points_attempted');
            $table->integer('steals')->default(0)->after('assists');
            $table->integer('rebounds')->default(0)->after('steals');
        });
    }

    public function down()
    {
        Schema::table('player_game_stats', function (Blueprint $table) {
            $table->dropColumn(['assists', 'steals', 'rebounds']);
        });
    }
};