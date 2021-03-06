<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('corse_id')->references('id')->on('corses');
        });

        Schema::table('score_cards', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('game_id')->references('id')->on('games');
        });

        Schema::table('corse_holes', function (Blueprint $table) {
            $table->foreign('corse_id')->references('id')->on('corses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
