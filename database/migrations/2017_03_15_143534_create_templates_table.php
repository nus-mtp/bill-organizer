<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('record_issuer_id')->unsigned();
            $table->integer('issue_date_area_id')->unsigned();
            $table->integer('due_date_area_id')->unsigned()->nullable();
            $table->integer('period_area_id')->unsigned();
            $table->integer('amount_area_id')->unsigned();
            $table->foreign('record_issuer_id')->references('id')->on('record_issuers');
            $table->foreign('issue_date_area_id')->references('id')->on('field_areas');
            $table->foreign('due_date_area_id')->references('id')->on('field_areas');
            $table->foreign('period_area_id')->references('id')->on('field_areas');
            $table->foreign('amount_area_id')->references('id')->on('field_areas');
            $table->unique(['record_issuer_id', 'created_at']);
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
        Schema::dropIfExists('templates');
    }
}
