<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->default(2);
            $table->foreign('group_id')->references('id')->on('user_groups');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('language');
            $table->boolean('subscribe')->default(false);
            $table->boolean('email_confirmed')->default(false);
            $table->boolean('two_fa')->default(false);
            $table->text('google2fa_secret')->nullable();
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
        Schema::dropIfExists('users');
    }
}
