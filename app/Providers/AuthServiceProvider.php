<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Services\AbilityService;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        // $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            $abilities = app(AbilityService::class)
                ->abilitiesFor($user);
            return in_array($ability, $abilities) ?: null;
        });
    }
}
