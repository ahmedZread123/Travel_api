<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRentelsTable extends Migration
{
    /**
     * Run the migrations.
     *  [id - Rental_Agency - coformation - description ]
     *          plck ip [location_name - address - date - time ]
     *         Drop off [location_name - address - date - time]
     *         details  [car_details - car_type]
     * @return void
     */
    public function up()
    {
        Schema::create('car_rentels', function (Blueprint $table) {
            $table->id();
            $table->string('rental_agency') ;
            $table->string('coformation') ;
            $table->string('description') ;
            $table->string('pick_up_location_name') ;
            $table->string('pick_up_address') ;
            $table->date('pick_up_date') ;
            $table->time('pick_up_time') ;
            $table->string('drop_off_location_name') ;
            $table->string('drop_off_address') ;
            $table->date('drop_off_date') ;
            $table->time('drop_off_time') ;
            $table->string('car_details') ;
            $table->string('car_type') ;
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
        Schema::dropIfExists('car_rentels');
    }
}
