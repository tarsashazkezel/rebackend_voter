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
        if (isset($data['role_id'])) {
            $user->role_id = $data['role_id'];
        }

        if (isset($data['ownership_ratio'])) {
            $user->ownership_ratio = $data['ownership_ratio'];
        }

        $user->save();

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
}
