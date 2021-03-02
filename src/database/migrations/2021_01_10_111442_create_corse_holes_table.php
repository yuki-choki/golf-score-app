<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorseHolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corse_holes', function (Blueprint $table) {
            $table->id();
            $table->integer('corse_id');
            $table->string('hole', 10);
            $table->integer('back')->nullable();
            $table->integer('regular')->nullable();
            $table->integer('front')->nullable();
            $table->integer('gold')->nullable();
            $table->integer('ladies')->nullable();
            $table->integer('par')->nullable();
            $table->integer('hd')->nullable();
            $table->string('update_job', 50);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->default(DB::raw('NULL on update CURRENT_TIMESTAMP'));
            $table->tinyInteger('delete_flg')->default(0);
        });

        Schema::table('posts', function (Blueprint $table) {
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
        Schema::dropIfExists('corse_holes');
    }
}
