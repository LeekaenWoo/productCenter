<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeSetEntitiesTable extends Migration
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
		attribute_set_id	int
		attribute_id	int
		created_at	datetime
		updated_at	datetime
		*/
		Schema::create('attribute_set_entities', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('attribute_set_id')->unsigned();
			$table->integer('attribute_id')->unsigned();
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
