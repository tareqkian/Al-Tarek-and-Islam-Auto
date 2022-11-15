<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanBrandTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoban_brand_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autoban_brand_id')->unsigned();
            $table->string("locale")->index();
            $table->string("brand_title");

           $table->foreign('autoban_brand_id')->references('id')->on('autoban_brands')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autoban_brand_translations');
    }
}
