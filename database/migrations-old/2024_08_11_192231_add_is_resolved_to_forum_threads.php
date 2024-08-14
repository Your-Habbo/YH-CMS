<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->boolean('requires_solution')->default(false);
            $table->boolean('is_resolved')->default(false);
        });
    }

    public function down()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn(['requires_solution', 'is_resolved']);
        });
    }
};