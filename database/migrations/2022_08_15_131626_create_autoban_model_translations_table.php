<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutobanModelTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoban_model_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autoban_model_id')->unsigned();
            $table->string('locale');
            $table->string('model_title');

            $table->unique(['autoban_model_id', 'locale']);
            $table->foreign('autoban_model_id')->references('id')->on('autoban_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autoban_model_translations');
    }
}
