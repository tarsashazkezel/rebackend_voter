<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponse;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Token generálása (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            
            return response()->json([
                'message' => 'Sikeres bejelentkezés',
                'token' => $token,
                'user' => UserResource::make($user),
            ]);
        }

        return response()->json(['message' => 'Érvénytelen e-mail vagy jelszó'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sikeres kijelentkezés']);
    }
    
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $token = Str::random(60);
        Cache::put('temp_user'.$token, $data, now()->addMinutes(10));
        Mail::to($data['email'])->send(new RegisterMail($token, $data['name']));
        return response()->json(["data" => null,"message" => "Megerősítő email elküldve, kérlek erősítsd meg a regisztrációt"], 201);   
    }

    public function confirmRegistration(Request $request)
    {
        $token = $request->token;
        $data = Cache::get('temp_user'.$token);
        if (!$data) {
            return $this->response(null, 'A link lejárt vagy érvénytelen.', 400);
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>bcrypt($data['password']),
            'ownership_ratio' => 0, ['ownership_ratio'], //alapbol 0, az admin fogja modositani
            'role_id' => 2, // pl. tulajdonos
            "email_verified_at" => now()
        ]);
        Cache::forget('temp_user'.$token);
        return $this->response(['user' => $user], 'Sikeres megerősítés és regisztráció!', 201);
    }
}
