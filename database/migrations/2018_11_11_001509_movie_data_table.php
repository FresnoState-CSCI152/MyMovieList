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
            $table->boolean('action')->default(false);
            $table->boolean('adventure')->default(false);
            $table->boolean('animation')->default(false);
            $table->boolean('comedy')->default(false);
            $table->boolean('crime')->default(false);
            $table->boolean('documentary')->default(false);
            $table->boolean('drama')->default(false);
            $table->boolean('family')->default(false);
            $table->boolean('fantasy')->default(false);
            $table->boolean('history')->default(false);
            $table->boolean('horror')->default(false);
            $table->boolean('music')->default(false);
            $table->boolean('mystery')->default(false);
            $table->boolean('romance')->default(false);
            $table->boolean('science_fiction')->default(false);
            $table->boolean('tv_movie')->default(false);
            $table->boolean('thriller')->default(false);
            $table->boolean('war')->default(false);
            $table->boolean('western')->default(false);
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
