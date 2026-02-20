<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponse;

class AuthenticationController extends Controller
{   
    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        $data = $request->validate();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>bcrypt($data['password']),
            'ownership_ratio' => $data['ownership_ratio'],
            'role_id' => 2, // pl. tulajdonos
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(['token' => $token,'user' => $user],'Sikeres regisztráció', 201);
    }
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error("Hibás email vagy jelszó", 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $user,
        ],"Sikeres bejelentkezés", 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return $this->success([] ,"Sikeres kijelentkezés"  ,200);
    }

}
