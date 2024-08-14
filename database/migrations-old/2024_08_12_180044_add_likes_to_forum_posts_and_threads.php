<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLikesToForumPostsAndThreads extends Migration
{
    public function up()
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->unsignedInteger('likes_count')->default(0);
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->unsignedInteger('likes_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });
    }
}

