<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsEditedToForumPostsAndThreads extends Migration
{
    public function up()
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->boolean('is_edited')->default(false)->after('content');
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->boolean('is_edited')->default(false)->after('content');
        });
    }

    public function down()
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn('is_edited');
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn('is_edited');
        });
    }
}
