<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionCategoryTranslationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('option_category_translations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('option_category_id')
        ->unsigned()
        ->references('id')
        ->on('option_categories')
        ->onDelete('cascade');
      $table->string('locale');
      $table->string('option_category_title');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('option_category_translations');
  }
}
