<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function forgotPassword (Request $request)
    {
        try {

            $user = User::query()->where('email', $request->input('email'))->first();

            if (!$user) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => 'Usuario no existe'
                ], 404);
            }

            $token = Str::random(64);
            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('email.recuperar-contrasenia', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Recuperar Contraseña');
            });

            return response()->json([
                'response' => 'success',
                'data' => [
                    'message' => 'Email enviado con éxito',
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

    public function verifyPasswordResetToken(Request $request, $token)
    {
        try {

            $tokenData = DB::table('password_reset_tokens')
                ->where('token', $token)->first();

            if(!$tokenData) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => 'Token no válido'
                ], 303);
            }

            return response()->json([
                'response' => 'success',
                'data' => 'Token válido',
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

    public function updatePassword(Request $request, $token)
    {
        try {

            $tokenData = DB::table('password_reset_tokens')
                ->where('token', $token)->first();

            if(!$tokenData) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => 'Token no válido'
                ], 303);
            }

            $user = User::query()->where('email', $tokenData->email)->first();

            if(!$user) {
                return response()->json([
                    'response' => 'error',
                    'data' => null,
                    'error' => 'Token no válido'
                ], 303);
            }

            $user->update([
                'password' => bcrypt($request->input('password'))
            ]);

            DB::table('password_reset_tokens')
                ->where('token', $token)->delete();

            return response()->json([
                'response' => 'success',
                'data' => [
                    'message' => 'Password modificado correctamente',
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
