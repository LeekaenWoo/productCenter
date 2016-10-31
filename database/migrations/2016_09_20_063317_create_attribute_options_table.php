<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeOptionsTable extends Migration
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
		attribute_id	int
		label	varchar(64)
		value	text
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::create('attribute_options', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('attribute_id')->unsigned();
			$table->string('label', 64);
			$table->text('value');
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
