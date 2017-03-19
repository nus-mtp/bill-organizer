<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('record_issuer_id')->unsigned();
            $table->integer('template_id')->unsigned()->nullable();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('period')->nullable();
            $table->boolean('has_values')->nullable();
            $table->double('amount', 15, 2)->nullable();
            $table->string('path_to_file', 1024);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('record_issuer_id')->references('id')->on('record_issuers');
            $table->foreign('template_id')->references('id')->on('templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_records');
    }
}
