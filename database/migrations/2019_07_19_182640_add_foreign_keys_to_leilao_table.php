<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLeilaoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('leilao', function(Blueprint $table)
		{
			$table->foreign('user_id', 'leilao_ibfk_1')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('leilao', function(Blueprint $table)
		{
			$table->dropForeign('leilao_ibfk_1');
		});
	}

}
