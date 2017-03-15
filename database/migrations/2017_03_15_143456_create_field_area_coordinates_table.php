<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFieldAreaCoordinatesTable
 *
 * Attributes:
 *      x, y -> top left (x,y) coordinate
 *      w, h -> width x height of the rectangular area that encapsulate a field's value in record
 */
class CreateFieldAreaCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_area_coordinates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('x');
            $table->integer('y');
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
        Schema::dropIfExists('field_area_coordinates');
    }
}
