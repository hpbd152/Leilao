<?php

namespace App\Http\Controllers;

use App\User;
use App\Leilao;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LeilaoController extends Controller
{
    public function abrirSolicitacao(Request $request){
        return $request;
    }

    public function getLeilao()
    {
        $controll = new \App\Http\Controllers\UserController();
        $user = $controll->getUserByToken();
        if($user != 'error'){

            $agora = date("Y-m-d H:i:s");

            $leilao = DB::table('leilao')->where('data_finalizacao', '>', $agora)
            ->where('ativo', '=', 1)->get();

            return response()->json($leilao);
        }
        else return 'error';
    }

    public function criarLeilao(Request $request)
    {

        $controll = new \App\Http\Controllers\UserController();
        $user = $controll->getUserByToken();
        if($user != 'error'){

            $request->validate([
                'titulo' => 'required',     
                'descricao' => 'required',      
                'valor_inicial' => 'required',       
                'condicao' => 'required',  
            ]);

            $leilao = new Leilao;
            $retorno = $leilao->cadastra($request, $user->id);

            return $retorno;
        }

        return [
                'error' => true,
                'message' => "Falha ao cadastrar leilão",
                'titulo' => "ERROR"
            ];

    }

    public function darLance(Request $request)
    {

        $controll = new \App\Http\Controllers\UserController();
        $user = $controll->getUserByToken();
        if($user != 'error'){

            $request->validate([
                'lance_vencedor' => 'required'
            ]);

            $leilao = new Leilao;   
            $retorno = $leilao->darLance($request, $user->id);

            return $retorno;
        }

        return [
                'error' => true,
                'message' => "Falha ao cadastrar seu lance",
                'titulo' => "ERROR"
            ];

    }

    public function excluiLeilao(Request $request)
    {

        $controll = new \App\Http\Controllers\UserController();
        $user = $controll->getUserByToken();
        if($user != 'error'){

            $leilao = new Leilao;   
            $retorno = $leilao->excluiLeilao($request);

            return $retorno;
        }

        return [
                'error' => true,
                'message' => "Falha ao excluir Leilão",
                'titulo' => "ERROR"
            ];

    }

    public function editaLeilao(Request $request)
    {

        $controll = new \App\Http\Controllers\UserController();
        $user = $controll->getUserByToken();
        if($user != 'error'){
            
            $request->validate([
                'titulo' => 'required',
                'descricao' => 'required',
                'condicao' => 'required',
            ]);

            $leilao = DB::table('leilao')->where('id_leilao',$request['id_leilao'])->first();

            if($leilao->titulo != $request['titulo'] || $leilao->descricao != $request['descricao'] || $leilao->condicao != $request['condicao'] || $leilao->url_foto != $request['url_foto'] ){
                $leilao = new Leilao;   
                $retorno = $leilao->editaLeilao($request);
                return $retorno;
            }

            return [
                'error' => false,
                'message' => "Sucesso ao editar seu leilão",
                'titulo' => "Sucesso!"
            ];


        }

        return [
                'error' => true,
                'message' => "Falha ao editar Leilão",
                'titulo' => "ERROR"
            ];

    }
    
}
