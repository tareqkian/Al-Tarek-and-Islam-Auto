<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionClassTranslationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('option_class_translations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('option_class_id')
        ->unsigned()
        ->references('id')
        ->on('option_classes')
        ->onDelete('cascade');
      $table->string('locale');
      $table->string('option_class_title');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('option_class_translations');
  }
}
