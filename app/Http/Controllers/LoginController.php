<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => $validator->messages()->all()
                ], 406);
            }

            $credentials = $request->only('email', 'password');

            if(!Auth::attempt($credentials)){
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => 'Usuario y/o contraseña no validos'
                ], 401);
            }

            $accessToken = Auth::user()->createToken('authToken')->accessToken;

            return response()->json([
                'response' => 'success',
                'data' => [
                    'user' => Auth::user(),
                    'token' => $accessToken
                ],
                'error' => null
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function register (Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => $validator->messages()->all()
                ], 406);
            }

            $user = User::create([
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => \Hash::make($request->input('password')),
            ]);

            $credentials = $request->only('email', 'password');

            Auth::attempt($credentials);

            $accessToken = Auth::user()->createToken('authToken')->accessToken;

            return response()->json([
                'response' => 'success',
                'data' => [
                    'user' => Auth::user(),
                    'token' => $accessToken
                ],
                'error' => null
            ], 201);

        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        $accessToken = Auth::user()->token();

        \DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json(["message" => "Se sesión ha finalizado"], 200);
    }

    public function me()
    {
        $user = Auth::user()->select('name', 'email')->first();
        try {
            return response()->json([
                'response' => 'success',
                'data' => [
                    'user' => $user,
                ],
                'error' => null
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
