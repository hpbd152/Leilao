<?php

namespace App\Http\Controllers;

    use Illuminate\Support\Facades\DB;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class UserController extends Controller
    {
        public function authenticate(Request $request)
        {
            $credentials = $request->only('usuario', 'password');

            if(DB::table('users')->where('usuario', $request['usuario'])->first()->desativado == 1){
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            return response()->json($token);
        }


        public function getAuthenticatedUser()
        {
            try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                        return response()->json(['user_not_found'], 404);
                }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json($user);
        }

        public function getUserByToken()
        {
            try {
                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return 'error';
                }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return 'error';
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return 'error';
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return 'error';
            }
            return $user;
        }

        public function getUsers(Request $request){
            $controll = new \App\Http\Controllers\UserController();
            $user = $controll->getUserByToken();
            if($user != 'error'){

                $users = DB::table('users')->get();

                return response()->json($users);
            }
            else return 'error';
        }

        public function editaUsuario(Request $request){
            $controll = new \App\Http\Controllers\UserController();
            $user = $controll->getUserByToken();
            if($user != 'error'){

                $request->validate([
                    'usuario' => 'required',
                    'situacao' => 'required',
                ]);

                $user = new User;
                if($request['situacao'] == "Ativo")
                    $retorno = $user->editaUsuario($request, 0);
                else
                    $retorno = $user->editaUsuario($request, 1);

                return $retorno;
            }
            else return 'error';
        }

    }
