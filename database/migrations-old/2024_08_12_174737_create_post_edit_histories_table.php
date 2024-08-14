<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostEditHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('post_edit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('old_content');
            $table->text('new_content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_edit_histories');
    }
}
