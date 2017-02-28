<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRecordIssuersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_record_issuers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->integer('type')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('type')->references('id')->on('record_issuer_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['name', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_record_issuers');
    }
}
