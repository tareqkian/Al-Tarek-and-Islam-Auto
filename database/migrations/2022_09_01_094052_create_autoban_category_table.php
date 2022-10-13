<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanCategoryTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('autoban_category', function (Blueprint $table) {
      $table->id();
      $table->foreignId('autoban_id')->constrained();
      $table->foreignId('option_category_id')->constrained();
      $table->string('option')->nullable();
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
    Schema::dropIfExists('autoban_category');
  }
}
