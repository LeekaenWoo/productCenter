<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteProductsTable extends Migration
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
		website_id	int
		original_sku	varchar(64)
		name	varchar(64)
		sku	varchar(64)
		price	decimal(12,4)
		special_price	decimal(12,4)
		cost	demical(12,4)
		status	tinyint 
		visibility	smallint(5) UNSIGNED
		description	text
		qty	int
		meta_title	
		meta_keyword	
		meta_description	
		sync_at	datetime
		created_at	datetime
		updated_at	datetime
		*/
		
		Schema::create('website_products', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->integer('website_id')->unsigned();

			$table->string('original_sku', 64);
			$table->string('name', 64);
			$table->string('sku', 64);
			
			$table->decimal('price', 12, 4);
			$table->decimal('special_price', 12, 4);
			$table->decimal('cost', 12, 4);
			$table->integer('qty')->unsigned();
			$table->tinyInteger('status');
			$table->smallInteger('visibility');
			
			$table->text('description');
			$table->text('meta_title');
			$table->text('meta_keyword');
			$table->text('meta_description');
			
			$table->timestamp('sync_at');
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
