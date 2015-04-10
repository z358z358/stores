<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAttrsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_attrs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('store_id')->unsigned();
			$table->text('content');
			$table->timestamps();

			$table->foreign('store_id')
				->references('id')
				->on('stores')
				->onDelete('cascade');
		});

		Schema::create('item_attr_item', function(Blueprint $table)
		{
			$table->integer('item_id')->unsigned()->index();
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

			$table->integer('item_attr_id')->unsigned()->index();
			$table->foreign('item_attr_id')->references('id')->on('item_attrs')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('item_attr_item');
		Schema::drop('item_attrs');
	}

}
