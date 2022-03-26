<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestauantsTable extends Migration
{
    /**
     * Run the migrations.
     *  [id - name - cheack_in_date  - cheack_in_time  - time_zone -
     *       cheack_out_date - cheack_out_time - address - phone - email - website ]
     * @return void
     */
    public function up()
    {
        Schema::create('restauants', function (Blueprint $table) {
            $table->id();
            $table->string('name') ;
            $table->date('cheack_in_date') ;
            $table->time('cheack_in_time') ;
            $table->string('time_zone') ;
            $table->date('cheack_out_date') ;
            $table->time('cheack_out_time') ;
            $table->string('address') ;
            $table->string('phone') ;
            $table->string('email');
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
        Schema::dropIfExists('restauants');
    }
}
