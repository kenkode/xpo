<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMemberAdvancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('memberadvances', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('employee_id');
			$table->integer('fiscal_year');
			$table->string('type');
			$table->string('status');
			$table->string('amount',10);
			$table->date('date');
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
		Schema::drop('memberadvances');
	}

}
