<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *  [invite_from - invite_to -group_id  ]
     * @return void
     */
    public function up()
    {
        Schema::create('invite_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invite_from')->unsigned();
            $table->bigInteger('invite_to')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('invite_from')->references('id')->on('users');
            $table->foreign('invite_to')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('invite_groups');
    }
}
