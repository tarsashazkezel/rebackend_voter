<?php

namespace App\Traits;

use App\Models\Role;

trait HasRole
{
    /**
     * A felhasználó szerepköre
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Van-e adott szerepe
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Több szerep közül valamelyik
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->name, $roles);
    }

    /**
     * Admin ellenőrzés – gyakori shortcut
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Szavazhat-e a felhasználó
     * (pl. auditor nem szavazhat)
     */
    public function canVote(): bool
    {
        return !$this->hasRole('auditor');
    }
}
