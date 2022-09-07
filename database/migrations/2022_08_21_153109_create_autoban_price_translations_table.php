<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanPriceTranslationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('autoban_price_translations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('autoban_price_id')
        ->unsigned()
        ->references('id')
        ->on("autoban_prices")
        ->onDelete('cascade');
      $table->string('locale');
      $table->integer('official');
      $table->integer('commercial');
      $table->integer('sale');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('autoban_price_translations');
  }
}
