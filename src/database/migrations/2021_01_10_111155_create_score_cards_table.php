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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('game_id');
            $table->tinyInteger('start_flag')->default(0);
            $table->string('course_name');
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
            $table->integer('putter_1')->nullable();
            $table->integer('putter_2')->nullable();
            $table->integer('putter_3')->nullable();
            $table->integer('putter_4')->nullable();
            $table->integer('putter_5')->nullable();
            $table->integer('putter_6')->nullable();
            $table->integer('putter_7')->nullable();
            $table->integer('putter_8')->nullable();
            $table->integer('putter_9')->nullable();
            $table->integer('par_1')->nullable();
            $table->integer('par_2')->nullable();
            $table->integer('par_3')->nullable();
            $table->integer('par_4')->nullable();
            $table->integer('par_5')->nullable();
            $table->integer('par_6')->nullable();
            $table->integer('par_7')->nullable();
            $table->integer('par_8')->nullable();
            $table->integer('par_9')->nullable();
            $table->integer('yard_1')->nullable();
            $table->integer('yard_2')->nullable();
            $table->integer('yard_3')->nullable();
            $table->integer('yard_4')->nullable();
            $table->integer('yard_5')->nullable();
            $table->integer('yard_6')->nullable();
            $table->integer('yard_7')->nullable();
            $table->integer('yard_8')->nullable();
            $table->integer('yard_9')->nullable();
            $table->string('update_job', 50);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->default(DB::raw('NULL on update CURRENT_TIMESTAMP'));
            $table->tinyInteger('delete_flg')->default(0);
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
