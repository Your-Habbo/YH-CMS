<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHabbolinkTable extends Migration
{
    public function up()
    {
        Schema::create('user_habbolink', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('habbo_origin_name')->nullable();
            $table->string('habbo_unique_id')->nullable();
            $table->string('habbo_figure_string')->nullable();
            $table->string('habbo_member_since')->nullable();
            $table->string('habbo_origin_status')->default('Pending');
            $table->string('habbo_verification_code')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_habbolink');
    }
}
