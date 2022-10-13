<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanCategoryOptionTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('autoban_category_option', function (Blueprint $table) {
      $table->foreignId('autoban_category_id')
        ->unsigned()->references('id')
        ->on('autoban_category')->onDelete('cascade');
      $table->foreignId('option_id')->constrained();
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
    Schema::dropIfExists('autoban_category_option');
  }
}
