<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('options', function (Blueprint $table) {
      $table->id();
      $table->foreignId('option_category_id')
        ->unsigned()
        ->references('id')
        ->on('option_categories')
        ->onDelete('cascade');
      $table->string('abbreviation')->nullable();
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
    Schema::dropIfExists('options');
  }
}
