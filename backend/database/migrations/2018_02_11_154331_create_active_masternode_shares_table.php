<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiveMasternodeSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_masternode_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned()->unique();
            $table->foreign('node_id')->references('id')->on('active_masternodes');
            $table->string('price');
            $table->string('count');
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
        Schema::dropIfExists('active_masternode_shares');
    }
}
