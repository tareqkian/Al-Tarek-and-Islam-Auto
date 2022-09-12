<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanPriceTasksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('autoban_price_tasks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('autoban_brand_id')
        ->unsigned()
        ->references('id')
        ->on('autoban_brands')
        ->onDelete('cascade');
      $table->integer('duration');
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
    Schema::dropIfExists('autoban_price_tasks');
  }
}
