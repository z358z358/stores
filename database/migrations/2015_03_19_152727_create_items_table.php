<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
			//$table->increments('id');
			$table->integer('store_id')->unsigned();
			$table->integer('item_id')->unsigned();
			$table->string('name');
			$table->double('price');
			$table->integer('status');

			$table->timestamps();

			$table->primary(array('store_id', 'item_id'));

			$table->foreign('store_id')
				->references('id')
				->on('stores')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('items');
	}

}
