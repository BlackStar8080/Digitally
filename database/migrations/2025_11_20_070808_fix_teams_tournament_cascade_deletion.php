<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('teams', function (Blueprint $table) {
        $table->dropForeign(['tournament_id']);
        
        $table->foreign('tournament_id')
              ->references('id')
              ->on('tournaments')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('teams', function (Blueprint $table) {
        $table->dropForeign(['tournament_id']);
        
        $table->foreign('tournament_id')
              ->references('id')
              ->on('tournaments')
              ->onDelete('cascade');
    });
}
};
