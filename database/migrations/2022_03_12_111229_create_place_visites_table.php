<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceVisitesTable extends Migration
{
    /**
     * Run the migrations.
     * [id - name - address - date - time - time_zone - phone - email - website]
     * @return void
     */
    public function up()
    {
        Schema::create('place_visites', function (Blueprint $table) {
            $table->id();
            $table->string('name') ;
            $table->string('address') ;
            $table->date('date') ;
            $table->time('time') ;
            $table->string('time_zone') ;
            $table->string('phone') ;
            $table->string('email') ;
            $table->string('website') ;
            $table->unsignedBigInteger('trip_id') ;
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_visites');
    }
}
