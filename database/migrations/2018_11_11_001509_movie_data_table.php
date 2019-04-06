<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MovieDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_data', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tmdb_id');
            $table->float('tmdb_score');
            $table->string('title');
            $table->string('img_path');
            $table->string('release');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_data');
    }
}
