<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->tinyInteger('caty');

            $table->tinyInteger('locked')->default( 0 );

            $table->timestamps();

            $table->index('caty');
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
