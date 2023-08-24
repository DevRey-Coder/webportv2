<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Stock;
use App\Models\User;
use App\Policies\StockPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Stock::class => StockPolicy::class
    ];



    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('view-admin',fn(User $user)=>$user->role == "admin");
    }
}
