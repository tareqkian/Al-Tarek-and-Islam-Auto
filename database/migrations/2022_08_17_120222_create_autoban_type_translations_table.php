<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanTypeTranslationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('autoban_type_translations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('autoban_type_id')->unsigned();
      $table->string('locale');
      $table->string('type_title');

      $table->foreign('autoban_type_id')->references('id')->on('autoban_types')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('autoban_type_translations');
  }
}
