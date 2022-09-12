<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionTranslationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('option_translations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('option_id')
        ->unsigned()
        ->references('id')
        ->on('options')
        ->onDelete('cascade');
      $table->string('locale');
      $table->string('option_title');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('option_translations');
  }
}
