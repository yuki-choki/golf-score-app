<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('corse_id');
            $table->date('date');
            $table->text('memo')->nullable();
            $table->string('weather', 50);
            $table->string('update_job', 50);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->default(DB::raw('NULL on update CURRENT_TIMESTAMP'));
            $table->tinyInteger('delete_flg')->default(0);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('corse_id')->constrained('corses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
