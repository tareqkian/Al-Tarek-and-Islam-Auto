<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoban_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId("autoban_brand_id")->unsigned();
            $table->string("model_image");
            $table->timestamps();

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
        Schema::dropIfExists('autoban_models');
    }
}
