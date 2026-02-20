<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MeetingPolicy
{
    public function before(User $user, string $ability):?bool
    {
        return $user->role === 'admin' ? true : null;
    }
    public function view(User $user, Meeting $meeting): Response
    {
        return Response::allow();
    }

    public function create(User $user): Response
    {
        return $user->can('meeting.create')
            ? Response::allow()
            : Response::deny('Nincs jogosultság közgyűlés létrehozására.');
    }

    public function update(User $user, Meeting $meeting): Response
    {
        return $user->can('meeting.update')
            ? Response::allow()
            : Response::deny('Nem módosíthatod ezt a közgyűlést.');
    }

    public function delete(User $user, Meeting $meeting): Response
    {
        return $user->can('meeting.delete')
            ? Response::allow()
            : Response::deny('Nem törölheted ezt a közgyűlést.');
    }
}
