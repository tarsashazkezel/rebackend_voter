<?php

namespace App\Services;
use App\Models\User;
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
}
