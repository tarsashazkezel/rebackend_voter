<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
class UserService
{ 
    public function index()
    {
        return User::with('role')->get();
    }

    public function show(User $user): User
    {
        return $user->load('role');
    }

    public function update(User $user, array $data): User
{
    // A $user->update($data) parancs MINDENT elment, amit a Controller átad neki
    // (is_active, ownership_ratio, role_id stb.)
    $user->update($data);

    return $user;
}

    public function delete(User $user): void
    {
        $user->delete();
    }
    public function sendResetLink(array $credentials)
    {
        // A 'broker' küldi ki az e-mailt a Laravel alapértelmezett sablonjával
        return Password::sendResetLink($credentials);
    }

    public function resetPassword(array $data)
    {
        return Password::reset(
            $data,
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
    public function getUserId($username)
    {
        $user = User::where('name', $username)->first();
        return $user?$user->id:null;
    }
}
