<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponse;

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
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'ownership_ratio' => $user->ownership_ratio
                ]
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

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>bcrypt($data['password']),
            'ownership_ratio' => 0, ['ownership_ratio'], //alapbol 0, az admin fogja modositani
            'role_id' => 2, // pl. tulajdonos
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->response(['token' => $token,'user' => $user],'Sikeres regisztráció', 201);
    }
}
