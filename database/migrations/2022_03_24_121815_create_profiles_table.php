<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('photo')->nullable();
            $table->string('header_photo')->nullable();
            $table->boolean('gender')->default(0) ;
            $table->date('birth_day')->nullable() ;
            $table->string('nationality')->nullable() ;
            $table->string('language')->nullable() ;
            $table->boolean('host')->default(0) ;
            $table->string('interst')->nullable() ;
            $table->text('bio')->nullable() ;
            $table->unsignedBigInteger('user_id') ;
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('profiles');
    }
}
