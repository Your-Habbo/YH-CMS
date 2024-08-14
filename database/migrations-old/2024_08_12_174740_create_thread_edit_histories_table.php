<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadEditHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('thread_edit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('forum_threads')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('old_title')->nullable();
            $table->string('new_title')->nullable();
            $table->text('old_content');
            $table->text('new_content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thread_edit_histories');
    }
}
