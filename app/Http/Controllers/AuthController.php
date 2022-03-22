<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // validate user
        if (! $token = auth()->attempt($validator)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }else{
            // send token and user
            return response()->json([
                'token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user()
            ]);
        }


    }

    public function register(Request $request){
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $validator['name'],
            'email' => $validator['email'],
            'password' => bcrypt($validator['password']),
        ]);

        return response()->json([
            'message' => 'user created',
            'user' => $user
        ], 201);
    }

    public function logout(){
        auth()->logout();
        return response()->json([
            'message' => 'user logged out'
        ]);
    }

    public function refresh(){
        return response()->json([
            'token' => auth()->refresh(),
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function userData(){
        return response()->json(auth()->user());
    }
}
