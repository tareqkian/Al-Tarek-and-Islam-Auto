<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewedToAutobansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('autobans', function (Blueprint $table) {
      $table->integer('reviewed')->default(0)->after('order');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('autobans', function (Blueprint $table) {
      $table->dropColumn('reviewed');
    });
  }
}
