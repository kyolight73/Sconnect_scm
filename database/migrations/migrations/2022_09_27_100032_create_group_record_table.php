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
        Schema::create('group_record', function (Blueprint $table) {
            $table->date('record_date');
            $table->bigInteger('group_id');
            $table->string('name');
            $table->integer('member_count');
            $table->timestamps();
            $table->primary(['record_date', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_record');
    }
};
