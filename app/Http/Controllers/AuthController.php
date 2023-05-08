<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $user = new User();

        $user->login = $request->login;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $credentials = $request->only('login', 'password');

        if(!$token = auth()->attempt($credentials)){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'isLogin' => false
                ]
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }
}
