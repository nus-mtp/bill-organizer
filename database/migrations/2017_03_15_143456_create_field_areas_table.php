<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFieldAreasTable
 *
 * Attributes:
 *      x, y -> top left (x,y) coordinate
 *      w, h -> width x height of the rectangular area that encapsulate a field's value in record
 */
class CreateFieldAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page')->unsigned();
            $table->integer('x')->unsigned();
            $table->integer('y')->unsigned();
            $table->integer('w')->unsigned();
            $table->integer('h')->unsigned();
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
        Schema::dropIfExists('field_areas');
    }
}
