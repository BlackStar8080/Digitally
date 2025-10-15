<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('player_game_stats', function (Blueprint $table) {
            $table->integer('blocks')->default(0)->after('rebounds');
        });
    }

    public function down()
    {
        Schema::table('player_game_stats', function (Blueprint $table) {
            $table->dropColumn('blocks');
        });
    }
};