<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('autobans', function (Blueprint $table) {
      $table->id();
      $table->foreignId('autoban_model_id')
        ->unsigned()
        ->references('id')
        ->on('autoban_models');
      $table->foreignId('autoban_type_id')
        ->unsigned()
        ->references('id')
        ->on('autoban_types');
      $table->foreignId('autoban_year_id')
        ->unsigned()
        ->references('id')
        ->on('autoban_years');
      $table->integer('order');
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
    Schema::dropIfExists('autobans');
  }
}
