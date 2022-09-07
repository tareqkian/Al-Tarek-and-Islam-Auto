<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToOptionSubClassesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('option_sub_classes', function (Blueprint $table) {
      $table->integer('order')->after('option_class_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('option_sub_classes', function (Blueprint $table) {
      $table->dropColumn('order');
    });
  }
}
