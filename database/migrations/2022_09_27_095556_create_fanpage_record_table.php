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
        Schema::create('fanpage_record', function (Blueprint $table) {
            $table->date('record_date');
            $table->bigInteger('page_id');
            $table->string('name');
            $table->integer('likes_count');
            $table->timestamps();
            $table->primary(['record_date', 'page_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fanpage_record');
    }
};
