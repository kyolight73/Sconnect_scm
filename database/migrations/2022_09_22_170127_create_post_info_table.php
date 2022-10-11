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
        Schema::create('post_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id');
            $table->string('post_id');
            $table->string('message', 200);
            $table->dateTime('post_create');
            $table->string('link', 200);
            $table->integer('angry_count');
            $table->integer('sad_count');
            $table->integer('like_count');
            $table->integer('love_count');
            $table->integer('wow_count');
            $table->integer('haha_count');
            $table->integer('shares_count');
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
        Schema::dropIfExists('post_info');
    }
};
