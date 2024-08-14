<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->boolean('is_guide')->default(false);
            $table->boolean('is_helpful')->default(false);
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->boolean('is_solution')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('contribution_points')->default(0);
        });

        Schema::table('thread_tags', function (Blueprint $table) {
            $table->boolean('is_help_tag')->default(false);
        });
    }

    public function down()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn(['is_guide', 'is_helpful']);
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn('is_solution');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('contribution_points');
        });

        Schema::table('thread_tags', function (Blueprint $table) {
            $table->dropColumn('is_help_tag');
        });
    }
};