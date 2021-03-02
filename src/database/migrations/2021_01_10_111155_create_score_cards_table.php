<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoreCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('game_id');
            $table->string('player_name');
            $table->integer('score_1')->nullable();
            $table->integer('score_2')->nullable();
            $table->integer('score_3')->nullable();
            $table->integer('score_4')->nullable();
            $table->integer('score_5')->nullable();
            $table->integer('score_6')->nullable();
            $table->integer('score_7')->nullable();
            $table->integer('score_8')->nullable();
            $table->integer('score_9')->nullable();
            $table->integer('score_10')->nullable();
            $table->integer('score_11')->nullable();
            $table->integer('score_12')->nullable();
            $table->integer('score_13')->nullable();
            $table->integer('score_14')->nullable();
            $table->integer('score_15')->nullable();
            $table->integer('score_16')->nullable();
            $table->integer('score_17')->nullable();
            $table->integer('score_18')->nullable();
            $table->integer('putter_1')->nullable();
            $table->integer('putter_2')->nullable();
            $table->integer('putter_3')->nullable();
            $table->integer('putter_4')->nullable();
            $table->integer('putter_5')->nullable();
            $table->integer('putter_6')->nullable();
            $table->integer('putter_7')->nullable();
            $table->integer('putter_8')->nullable();
            $table->integer('putter_9')->nullable();
            $table->integer('putter_10')->nullable();
            $table->integer('putter_11')->nullable();
            $table->integer('putter_12')->nullable();
            $table->integer('putter_13')->nullable();
            $table->integer('putter_14')->nullable();
            $table->integer('putter_15')->nullable();
            $table->integer('putter_16')->nullable();
            $table->integer('putter_17')->nullable();
            $table->integer('putter_18')->nullable();
            $table->integer('par_1')->nullable();
            $table->integer('par_2')->nullable();
            $table->integer('par_3')->nullable();
            $table->integer('par_4')->nullable();
            $table->integer('par_5')->nullable();
            $table->integer('par_6')->nullable();
            $table->integer('par_7')->nullable();
            $table->integer('par_8')->nullable();
            $table->integer('par_9')->nullable();
            $table->integer('par_10')->nullable();
            $table->integer('par_11')->nullable();
            $table->integer('par_12')->nullable();
            $table->integer('par_13')->nullable();
            $table->integer('par_14')->nullable();
            $table->integer('par_15')->nullable();
            $table->integer('par_16')->nullable();
            $table->integer('par_17')->nullable();
            $table->integer('par_18')->nullable();
            $table->integer('yard_1')->nullable();
            $table->integer('yard_2')->nullable();
            $table->integer('yard_3')->nullable();
            $table->integer('yard_4')->nullable();
            $table->integer('yard_5')->nullable();
            $table->integer('yard_6')->nullable();
            $table->integer('yard_7')->nullable();
            $table->integer('yard_8')->nullable();
            $table->integer('yard_9')->nullable();
            $table->integer('yard_10')->nullable();
            $table->integer('yard_11')->nullable();
            $table->integer('yard_12')->nullable();
            $table->integer('yard_13')->nullable();
            $table->integer('yard_14')->nullable();
            $table->integer('yard_15')->nullable();
            $table->integer('yard_16')->nullable();
            $table->integer('yard_17')->nullable();
            $table->integer('yard_18')->nullable();
            $table->string('update_job', 50);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->default(DB::raw('NULL on update CURRENT_TIMESTAMP'));
            $table->tinyInteger('delete_flg')->default(0);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('game_id')->constrained('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_cards');
    }
}
