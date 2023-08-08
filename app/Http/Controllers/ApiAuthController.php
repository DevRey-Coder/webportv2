<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "message" => "User register successful",
        ]);

    }
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]);

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                "message" => "Username or password wrong",
            ]);
        }

        return Auth::user()->createToken('admin-token',['admin']);

    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successful"
        ]);
    }

    public function logoutAll(){
        foreach (Auth::user()->tokens as $token) {
            $token->delete();
        }
        return response()->json([
            "message" => "logout all devices successful"
        ]);
    }

    // public function devices(){
    //     return Auth::user()->tokens;
    // }
}
