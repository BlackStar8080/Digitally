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
    Schema::table('teams', function (Blueprint $table) {
        $table->unsignedBigInteger('tournament_id')->nullable()->after('sport_id');

        $table->foreign('tournament_id')
              ->references('id')
              ->on('tournaments')
              ->onDelete('cascade'); // Your old behavior
    });
}

public function down()
{
    Schema::table('teams', function (Blueprint $table) {
        $table->dropForeign(['tournament_id']);
        $table->dropColumn('tournament_id');
    });
}

};
