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
        Schema::create('shortlink_record', function (Blueprint $table) {
            $table->id();
            $table->string('country_code');
            $table->string('link_id');
            $table->string('guid');
            $table->string('title');
            $table->integer('click_count');
            $table->date('record_reference');
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
        Schema::dropIfExists('shortlink_record');
    }
};
