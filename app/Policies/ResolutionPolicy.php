<?php

namespace App\Policies;

use App\Models\Resolution;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ResolutionPolicy
{
    public function before(User $user, string $ability):?bool
    {
        return $user->role === 'admin' ? true : null;
    }
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }
    public function view(User $user, Resolution $resolution): Response
    {
        return Response::allow();
    }
   public function create(User $user): Response
    {
        return $user->canDo('resolution.create')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user): Response
    {
        return $user->canDo('resolution.update')
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user): Response
    {
        return $user->canDo('resolution.delete')
            ? Response::allow()
            : Response::deny();
    }

}
