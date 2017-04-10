<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('record_issuer_id')->unsigned();
            $table->integer('template_id')->unsigned()->nullable();
            $table->boolean('temporary');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('period')->nullable();
            $table->double('amount', 15, 2)->nullable();
            $table->string('path_to_file', 1024)->nullable(); // this should be unique, but unique constraint creates an index so i'm not adding it
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('record_issuer_id')->references('id')->on('record_issuers');
            $table->foreign('template_id')->references('id')->on('templates');
            $table->unique(['record_issuer_id', 'issue_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
