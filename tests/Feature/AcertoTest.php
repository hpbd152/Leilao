<?php

namespace Tests\Feature;
require './vendor/autoload.php';
use App\Area;
use App\estado;
use App\cidade;
use App\bairro;
use App\endereco;
use App\Historic;
use App\Entrega;
use App\User;
use App\tipo_usuario;
use App\biker;
use App\Balance;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Acerto;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcertoTest extends TestCase
{
	use DatabaseTransactions;
    use WithoutMiddleware;

    public function testAcerto_sucess()
    {
        $client = new GuzzleHttp\Client([
            'headers' => [
                'User-Agent' => 'MyReader'
            ]
        ]);
        $userId = 50; //HENRIQUE PENNA
        $empId = 3; //BISTRÔ UNIVERSITÁRIO
        $bairroId = 2; //CAMPUS UFV
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU2NzAwMzEwNCwiZXhwIjoxNTY3NjA3OTA0LCJuYmYiOjE1NjcwMDMxMDQsImp0aSI6IjZ0Wm1wQXY5aU05eDFxaloiLCJzdWIiOjMsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.7cY6WCLWyeYLujbMAT3DT34vGyFz1Ku7Qs9y1fNgnUg";
       
        $response = $client->request('GET', '/api/cidades', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ],
        ]);
        dd($response);

        $vezes = 5;
        $controll = new \App\Http\Controllers\EntregaController();
        for($i = 0; $i < $vezes; $i++ ) {
            $test = $controll->setEntregas($request[0]);
            $this->assertEquals(true, $test['ok']);
        }

        $this->assertDatabaseHas('balances',[
            'user_id'=> $empId,
            'saldo' => number_format(5*$vezes, 2, '.', '')
        ]);
        $this->assertDatabaseHas('balances',[
            'user_id'=> $userId,
            'saldo' => number_format(3*$vezes, 2, '.', '')
        ]);
        $this->assertDatabaseHas('historics',[
            'user_id'=> $empId,
            'total_after'=> number_format(5*$vezes, 2, '.', '')
        ]);
    }
}
/*
    public function testAcerto_fail()
    {
        $request = [ 
            Request::create('/store', 'POST',[
                'nome' => null,
                'email' => null,
                'password' => bcrypt(123456),
                'phone' => null,
                'cidade_id' => 1,
                'cpf' => null,
                'tipo' => 'PE' ]),
            Request::create('/store', 'POST',[
                'nome' => null,
                'email' => 'testing2@admin.com',
                'password' => bcrypt(123456),
                'phone' => '37994242356',
                'cidade_id' => 1,
                'cpf' => '08348988210',
                'tipo' => 'PE' ]),
            Request::create('/store', 'POST',[
                'nome' => 'Teste Acerto 3',
                'email' => null,
                'password' => bcrypt(123456),
                'phone' => '37994242356',
                'cidade_id' => 1,
                'cpf' => '08348938610',
                'tipo' => 'PE' ]),
            Request::create('/store', 'POST',[
                'nome' => 'Teste Acerto 4',
                'email' => 'saiejhasie@gmail.com',
                'password' => bcrypt(123456),
                'phone' => null,
                'cpf' => '08345988610',
                'cidade_id' => 1,
                'tipo' => 'PE' ]),
         ];
        $Acerto = new Acerto;
        $controll = new \App\Http\Controllers\CadastroController();

        foreach ($request as $req) {
            $test = $controll->Store($req, $Acerto);
            $this->assertEquals(302, $test->getStatusCode());
            $this->assertDatabaseMissing('Acertos',[
                'email'=> $req->email,
                'nome' => $req->nome,
            ]);
        }
        */
    //}
      
//}

