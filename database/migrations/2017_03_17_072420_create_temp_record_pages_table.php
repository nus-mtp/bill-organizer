<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempRecordPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_record_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('temp_record_id')->unsigned();
            $table->string('path', 1024);
            $table->foreign('temp_record_id')->references('id')->on('temp_records');
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
        Schema::dropIfExists('temp_record_pages');
    }
}
