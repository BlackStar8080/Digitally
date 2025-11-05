<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->enum('interface_mode', ['all_in_one', 'separated'])->default('all_in_one')->after('is_bye');
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('interface_mode');
        });
    }
};