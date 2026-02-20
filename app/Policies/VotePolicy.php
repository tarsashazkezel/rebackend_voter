<?php

namespace App\Policies;

use App\Models\User;

class VotePolicy
{
    public function before(User $user): bool
    {
        return $user->role === 'admin'? true : null;
    }
    public function vote(User $user, Resolution $resolution): Response
    {
        if (! $user->canDo('vote.cast')) {
            return Response::deny('Nincs jogosultság szavazni.');
        }

        if ($resolution->votes()->where('user_id', $user->id)->exists()) {
            return Response::deny('Már szavaztál erre a határozatra.');
        }

        return Response::allow();
    }
}
