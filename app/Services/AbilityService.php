<?php

namespace App\Services;
use App\Models\User;

class AbilityService
{
    public function abilitiesFor(User $user): array
    {
        return match ($user->role->name) {
            'admin' => [
                'meeting.create',
                'meeting.update',
                'meeting.delete',
                'agenda.create',
                'agenda.update',
                'agenda.delete',
                'resolution.create',
                'resolution.update',
                'resolution.delete',
                'vote.cast',
            ],

            'owner' => [
                'meeting.create',
                'agenda.create',
                'resolution.create',
                'vote.cast',
            ],

            'observer' => [
                'vote.cast',
            ],

            default => [],
        };
    }

    public function can(User $user, string $ability): bool
    {
        $role = $user->role?->name;

        if (! $role) {
            return false;
        }

        return in_array($ability, $this->abilities[$role] ?? []);
    }
}

