<?php

namespace App\Providers;

use App\Models\Batch;
use App\Models\Commodity;
use App\Models\Farm;
use App\Models\User;
use App\Policies\BatchPolicy;
use App\Policies\CommodityPolicy;
use App\Policies\FarmPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Batch::class, BatchPolicy::class);
        Gate::policy(Commodity::class, CommodityPolicy::class);
        Gate::policy(Farm::class, FarmPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
