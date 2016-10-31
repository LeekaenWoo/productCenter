<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
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
		group_id	int
		code	varchar(64)
		label	varchar(64)
		type	varchar(20)
		description	text
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('attribute_group_id')->unsigned();
			$table->string('code', 64);
			$table->string('label', 64);
			$table->string('type', 20);
			$table->text('description');
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
