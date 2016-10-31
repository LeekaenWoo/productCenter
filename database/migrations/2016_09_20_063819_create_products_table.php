<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
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
		sku	varchar(64)
		name	varchar(64)
		status	tinyint 
		quote	demical(12,4)
		description	text
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('attribute_set_id')->unsigned();
			$table->string('sku', 64);
			$table->string('name', 64);
			$table->tinyInteger('status');
			$table->decimal('quote', 12, 4);
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
