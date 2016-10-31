<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsitesTable extends Migration
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
		domain	varchar(64)
		IP	varchar(39)
		name	varchar(20)
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 64);
            $table->string('IP', 39);
            $table->string('name', 20);
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
        //
    }
}
