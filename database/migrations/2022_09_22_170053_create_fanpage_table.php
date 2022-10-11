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
        Schema::create('fanpage', function (Blueprint $table) {
            $table->id();
            $table->string('page_id', 200);
            $table->string('page_theme');
            $table->string('page_name');
            $table->string('picture', 400);
            $table->string('access_token', 300);
            $table->string('page_url');
            $table->integer('likes_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fanpage');
    }
};
