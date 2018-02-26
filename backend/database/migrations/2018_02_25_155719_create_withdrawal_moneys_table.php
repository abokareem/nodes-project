<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalMoneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_moneys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_bill_id')->unsigned();
            $table->foreign('user_bill_id')->references('id')->on('user_bills');
            $table->text('external_user_wallet');
            $table->enum('state', ['processing', 'approve', 'decline']);
            $table->string('amount');
            $table->softDeletes();
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
        Schema::dropIfExists('withdrawal_moneys');
    }
}
