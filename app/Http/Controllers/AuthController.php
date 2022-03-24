<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    // Login user
    public function login(Request $request){
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // validate user
        if (! $token = auth()->attempt($validator)) {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid credentials'
            ], 401);
        }else{
            // send token and user
            return response()->json([
                'status' => 'success',
                'data' => [
                    'token' => $token,
                    'expires_in' => auth()->factory()->getTTL() * 60,
                    'user' => auth()->user()
                ]
            ]);
        }
    }

    // Register user
    public function register(Request $request){
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

            $user = User::create([
                'name' => $validator['name'],
                'email' => $validator['email'],
                'password' => bcrypt($validator['password']),
            ]);

            return response()->json([
                'status' => 'success',
                'data' =>[
                    'user' => $user
                ]
            ], 201);
    }

    // Logout user
    public function logout(){
        auth()->logout();
        return response()->json([
            'status' => 'success',
            'data' => null
        ],200);
    }

    // Refresh user's token
    public function refresh(){
        if (auth()->user()){
            return response()->json([
                'status' => 'success',
                'data' => [
                    'token' => auth()->refresh(),
                    'expires_in' => auth()->factory()->getTTL() * 60,
                    'user' => auth()->user()
                ]
            ],200);
        }
    }

    // Get user's Profile
    public function userData(){
        if (auth()->user()){
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => auth()->user()
                ]
            ],200);
        }
    }
}
