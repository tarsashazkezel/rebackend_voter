<?php

namespace App\Policies;

use App\Models\User;

class AgendaItemPolicy
{
    public function before(User $user): bool
    {
        return $user->role === 'admin'? true : null;
    }
    public function __construct()
    {}
    public function create(User $user): Response
    {
        return $user->canDo('agenda.create')
            ? Response::allow()
            : Response::deny('Nincs jogosultság napirendi ponthoz.');
    }

    public function update(User $user): Response
    {
        return $user->canDo('agenda.update')
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user): Response
    {
        return $user->canDo('agenda.delete')
            ? Response::allow()
            : Response::deny();
    }
}
