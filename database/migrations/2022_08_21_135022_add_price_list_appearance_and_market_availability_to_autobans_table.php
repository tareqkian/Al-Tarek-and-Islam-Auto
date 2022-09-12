<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceListAppearanceAndMarketAvailabilityToAutobansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('autobans', function (Blueprint $table) {
      $table->boolean('price_list_appearance')
        ->after('autoban_year_id')
        ->default(1);
      $table->boolean('market_availability')
        ->after('price_list_appearance')
        ->default(1);
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
      $table->dropColumn('price_list_appearance');
      $table->dropColumn('market_availability');
    });
  }
}
