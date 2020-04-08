<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeilaoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leilao', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id_leilao');
			$table->string('titulo',191);
			$table->text('descricao');
			$table->float('valor_inicial', 9);
			$table->float('lance_vencedor', 9)->nullable();
			$table->string('condicao',191);
			$table->integer('user_id')->unsigned()->index('leilao_ibfk_1');
			$table->boolean('ativo')->default(1);
			$table->timestamp('data_abertura')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('data_finalizacao');
			$table->text('url_foto')->nullable();
			
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leilao');
	}

}
