<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanYearTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoban_year_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autoban_year_id')->unsigned();
            $table->string('locale');
            $table->string('year_number');

            $table->foreign('autoban_year_id')->references('id')->on('autoban_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autoban_year_translations');
    }
}
