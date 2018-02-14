<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasternodeBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masternode_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned()->unique();
            $table->foreign('node_id')->references('id')->on('masternodes');
            $table->string('amount')->default("0");
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
        Schema::dropIfExists('masternode_bills');
    }
}
