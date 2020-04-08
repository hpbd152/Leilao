<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Storage;

use DB;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario', 'password','foto_perfil','desativado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function editaUsuario($request, $desativado) : Array
    {   
        DB::beginTransaction();

        $leilao_cadastrado = $this::where('id', $request['id'])
        ->update([
            'desativado' => $desativado,
            'usuario' => $request['usuario']
        ]);


        if($leilao_cadastrado){
        	DB::commit();
            return [
                'error' => false,
                'message' => "Sucesso ao editar usuário",
                'titulo' => "Sucesso"
            ];
        }
        else{
        	DB::rollback();
            return [
                'error' => true,
                'message' => "Falha ao editar usuário",
                'titulo' => "ERROR"
            ];
        }
    }

}
