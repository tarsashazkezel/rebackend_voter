<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Csak admin láthatja a felhasználókat.');
    }

    public function view(User $user, User $model): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Nincs jogosultság.');
    }

    public function update(User $user, User $model): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Csak admin módosíthat felhasználót.');
    }

    public function delete(User $user, User $model): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Csak admin törölhet felhasználót.');
    }
}
