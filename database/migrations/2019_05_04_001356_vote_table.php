<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table)
        {
            $table->increments('id');

            $table->integer('user_id')->unsigned();

            $table->integer('votable_id')->unsigned();

            $table->string('votable_type');

            $table->integer('vote');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'votable_id', 'votable_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
