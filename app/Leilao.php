<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Leilao extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'leilao';

    protected $fillable = [
       'titulo','descricao','valor_inicial','condicao','lance_vencedor','usado','url_foto','user_id','ativo'
    ];

    public function cadastra($request, $user_id) : Array
    {   
        DB::beginTransaction();

        $leilao_cadastrado = $this::create([
            'titulo' => $request['titulo'],
            'descricao' => $request['descricao'],
            'valor_inicial' => $request['valor_inicial'],
            'lance_vencedor' => $request['valor_inicial'],
            'condicao' => $request['condicao'],
            'url_foto' => $request['url_foto'],
            'user_id' => $user_id
        ]);

        if($leilao_cadastrado){
        	DB::commit();
            return [
                'error' => false,
                'message' => "Sucesso ao cadastrar Leilão",
                'titulo' => "Sucesso!"
            ];
        }
        else{
        	DB::rollback();
            return [
                'error' => true,
                'message' => "Falha ao cadastrar Leilão",
                'titulo' => "ERROR"
            ];
        }
    }

    public function darLance($request, $user_id) : Array
    {   
        DB::beginTransaction();

        $leilao_cadastrado = $this::where('id_leilao', $request['id_leilao'])
          ->update(['lance_vencedor' => $request['valor']]);


        if($leilao_cadastrado){
        	DB::commit();
            return [
                'error' => false,
                'message' => "Sucesso ao cadastrar seu lance",
                'titulo' => "Sucesso!"
            ];
        }
        else{
        	DB::rollback();
            return [
                'error' => true,
                'message' => "Falha ao cadastrar seu lance",
                'titulo' => "ERROR"
            ];
        }
    }

    public function editaLeilao($request) : Array
    {   
        DB::beginTransaction();

        $leilao_cadastrado = $this::where('id_leilao', $request['id_leilao'])
          ->update([
              'titulo' => $request['titulo'],
              'descricao' => $request['descricao'],
              'condicao' => $request['condicao'],
              'url_foto' => $request['url_foto'],
              ]);


        if($leilao_cadastrado){
        	DB::commit();
            return [
                'error' => false,
                'message' => "Sucesso ao editar seu leilão",
                'titulo' => "Sucesso!"
            ];
        }
        else{
        	DB::rollback();
            return [
                'error' => true,
                'message' => "Falha ao editar seu leilão",
                'titulo' => "ERROR"
            ];
        }
    }

    public function excluiLeilao($request) : Array
    {   
        DB::beginTransaction();

        $leilao_cadastrado = $this::where('id_leilao', $request['id_leilao'])
          ->update(['ativo' => 0]);


        if($leilao_cadastrado){
        	DB::commit();
            return [
                'error' => false,
                'message' => "Seu leilão não aparecerá mais na lista de exibição.",
                'titulo' => "Sucesso ao excluir"
            ];
        }
        else{
        	DB::rollback();
            return [
                'error' => true,
                'message' => "Falha ao excluir Leilão",
                'titulo' => "ERROR"
            ];
        }
    }

}
