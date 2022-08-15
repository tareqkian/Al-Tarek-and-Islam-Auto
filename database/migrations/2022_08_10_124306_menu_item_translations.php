<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MenuItemTranslations extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('menu_item_translations', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger("menu_item_id");
      $table->string("locale")->index();
      $table->string("title");
      $table->unique(['menu_item_id','locale']);
    });

    Schema::table('menu_item_translations', function (Blueprint $table) {
      $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('menu_item_translations');
  }
}
