<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('referee')->nullable();
            $table->string('assistant_referee_1')->nullable();
            $table->string('assistant_referee_2')->nullable();
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn([
                'referee',
                'assistant_referee_1', 
                'assistant_referee_2',
            ]);
        });
    }
};