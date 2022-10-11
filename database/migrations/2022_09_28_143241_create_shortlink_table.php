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
        Schema::create('shortlink', function (Blueprint $table) {
            $table->id();
            $table->string('organization_guid');
            $table->string('guid');
            $table->string('link_id');
            $table->string('title');
            $table->string('access_token', 200);
            $table->date('link_date');
            $table->string('created_by');
            $table->string('short_url');
            $table->string('long_url');
            $table->integer('click_count');
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
        Schema::dropIfExists('shortlink');
    }
};
