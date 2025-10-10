<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'Scorekeeper_id');
            $table->renameColumn('name', 'Scorekeeper_name');
            $table->renameColumn('email', 'Scorekeeper_email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('Scorekeeper_id', 'id');
            $table->renameColumn('Scorekeeper_name', 'name');
            $table->renameColumn('Scorekeeper_email', 'email');
        });
    }
};

