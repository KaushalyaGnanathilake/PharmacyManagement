<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('owner_and_manager', function($user){
            return $user->hasAnyRoles(['Owner','Manager']);
        });

        Gate::define('owner_and_cashier', function($user){
            return $user->hasAnyRoles(['Owner','Cashier']);
        });
        
        Gate::define('owner', function($user){
            return $user->hasRole('Owner');
        });

        Gate::define('manager', function($user){
            return $user->hasRole('Manager');
        });

        Gate::define('cashier', function($user){
            return $user->hasRole('Cashier');
        });
    }
}
