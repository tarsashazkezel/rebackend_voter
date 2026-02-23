<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Kivettem a trait-et, mert hibát okozott
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
            'ownership_ratio' => 'required|numeric|min:0|max:10000',
            'role_id' => 'sometimes|required|exists:roles,id'
        ]);

        $updated = $this->service->update($user, $validated);

        return response()->json([
            'data' => new UserResource($updated),
            'message' => 'Felhasználó frissítve'
        ], 200);
    }

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->json(['message' => 'Felhasználó törölve'], 200);
    }
}