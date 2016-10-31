<?php

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
		Schema::dropIfExists('users');
		Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 64)->default('STAFF');
            $table->string('department', 32);
			$table->string('title', 32);
			$table->string('name', 20);
			$table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
