<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/**
		id	int
		type	varchar(64)
		department	varchar(32)
		title	varchar(32)
		name	varchar(20)
		login	varchar(20)
		password	varchar(20)
		last_ip	varchar(39)
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::dropIfExists('users');
		Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 64);
            $table->string('department', 32);
			$table->string('title', 32);
			$table->string('name', 20);
			$table->string('login', 20);
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
        Schema::dropIfExists('users');
    }
}
