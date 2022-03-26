<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *  [type- carrier - ]
     *    Departure [location_name - address - date -time - time_Zone]
     *    arrival   [location_name - address - date -time - time_Zone]
     * @return void
     */
    public function up()
    {
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->string('type') ;
            $table->string('carrier') ;
            $table->string('departure_location_name') ;
            $table->string('departure_address') ;
            $table->date('departure_date') ;
            $table->time('departure_time') ;
            $table->string('departure_time_zone') ;
            $table->string('arrival_location_name') ;
            $table->string('arrival_address') ;
            $table->date('arrival_date') ;
            $table->time('arrival_time') ;
            $table->string('arrival_time_zone') ;
            $table->unsignedBigInteger('trip_id') ;
            $table->foreign('trip_id')->references('id')->on('trips');
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
        Schema::dropIfExists('transportations');
    }
}
