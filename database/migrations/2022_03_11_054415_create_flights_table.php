<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *      [id - Depart_date - Air_line - flight_number - seat - confirmation  ]
     *     Departure مغادرة  [Airport - Depature Time - Time zone - Dep Terminal - Dep gate ]
     *      Arrival - وصول  [Airport - arrivel date - arrivel time - time zone - arr terminal - arr gate]
     */

    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->date('Depart_date');
            $table->string('Air_line');
            $table->string('flight_number');
            $table->string('seat');
            $table->string('confirmation');
            $table->string('Depart_Airport');
            $table->string('Depart_Time');
            $table->string('Depart_Time_Zone');
            $table->string('Depart_Terminal');
            $table->string('Depart_Gate');
            $table->string('Arrive_Airport');
            $table->date('Arrive_Date');
            $table->string('Arrive_Time');
            $table->string('Arrive_Time_Zone');
            $table->string('Arrive_Terminal');
            $table->string('Arrive_Gate');
            $table->unsignedBigInteger('trip_id');
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
        Schema::dropIfExists('flights');
    }
}
