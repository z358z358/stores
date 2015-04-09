<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttrsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attrs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('content');
			$table->timestamps();
		});

		Schema::create('item_attr', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('item_id')->unsigned()->index();
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

			$table->integer('attr_id')->unsigned()->index();
			$table->foreign('attr_id')->references('id')->on('attrs')->onDelete('cascade');

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
		Schema::drop('attrs');
	}

}
