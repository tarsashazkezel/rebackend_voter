<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    public function index()
    {
        // Ellenőrizzük az admint
        if (Auth::user()->role_id !== 1) {
            return response()->json(['message' => 'Nincs jogosultsága'], 403);
        }
        $users = User::where('role_id', 2)->get();

        // Szabványos Laravel válasz
        return response()->json([
            'data' => UserResource::collection($users),
            'message' => 'Felhasználók listája'
        ], 200);
    
    }

    public function show(User $user)
    {
        return response()->json([
            'data' => new UserResource($this->service->show($user)),
            'message' => 'Felhasználó adatai'
        ], 200);
    }

    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'ownership_ratio' => 'sometimes|numeric|min:0|max:10000',
        'role_id' => 'sometimes|exists:roles,id',
        'is_active' => 'sometimes|boolean' // Engedélyezzük a státusz küldését
    ]);

    $updated = $this->service->update($user, $validated);

    return response()->json([
        'data' => $updated,
        'message' => 'Felhasználó frissítve'
    ], 200);
}

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->json(['message' => 'Felhasználó törölve'], 200);
    }
    
    public function forgotPassword(Request $request)
    {
    $request->validate(['email' => 'required|email']);

    $status = $this->service->sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Az e-mailt elküldtük.'], 200)
        : response()->json(['message' => 'Nem tudtuk elküldeni az e-mailt.'], 400);
    }

    public function resetPassword(Request $request)
    {
        // Ez kiírja az összes beérkező adatot a laravel.log fájlba
    \Log::info('Reset próbálkozás:', $request->all());

    // Ellenőrizzük, hogy létezik-e egyáltalán token ehhez az emailhez
    $record = \DB::table('password_reset_tokens')->where('email', $request->email)->first();
    \Log::info('Adatbázisban lévő rekord:', (array)$record);
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed', // 'password_confirmation' mezőt is küldeni kell!
    ]);

    $status = $this->service->resetPassword($request->only(
        'email', 'password', 'password_confirmation', 'token'
    ));

    if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
        return response()->json(['message' => 'Sikeres!'], 200);
    }

    // Itt a titok: a __($status) lefordítja a Laravel hibaüzenetét
    return response()->json([
        'message' => __($status), 
        'internal_status' => $status // Ez kiírja pl. hogy "passwords.token" vagy "passwords.user"
    ], 400);
    }
}