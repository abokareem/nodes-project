<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiveMasternodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_masternodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('masternode_id')->unsigned()->unique();
            $table->foreign('masternode_id')->references('id')->on('masternodes');
            $table->enum('state', ['processing', 'stable', 'unstable', 'disbanded']);
            $table->enum('type', ['single', 'party']);
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
        Schema::dropIfExists('active_masternodes');
    }
}
