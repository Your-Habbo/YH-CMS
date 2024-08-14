<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('thread_tags', function (Blueprint $table) {
            $table->string('color')->default('#3490dc'); // Default to a blue color
        });
    }

    public function down()
    {
        Schema::table('thread_tags', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};