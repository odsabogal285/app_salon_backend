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
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => $validator->messages()
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
}
