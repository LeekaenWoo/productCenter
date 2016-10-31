<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaGalleriesTable extends Migration
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
		product_id	int
		user_id	int
		label	varchar(64)
		path	varchar(64)
		sort	int
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::create('media_galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->string('label', 64);
			$table->string('path', 64);
			$table->integer('sort');
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
