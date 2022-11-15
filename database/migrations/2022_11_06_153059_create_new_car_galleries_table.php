<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewCarGalleriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_car_galleries', function (Blueprint $table) {
      $table->id();
      $table->foreignId("autoban_model_id")
        ->unsigned()
        ->references('id')
        ->on('autoban_models')
        ->onDelete('cascade');
      $table->string('image');
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
    Schema::dropIfExists('new_car_galleries');
  }
}
