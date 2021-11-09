<?php

namespace App\Providers;

use App\Repositories\Members;
use App\Repositories\Interfaces\MembersInterface;
use App\Repositories\Teams;
use App\Repositories\Interfaces\TeamsInterface;
use App\Repositories\Tournaments;
use App\Repositories\Interfaces\TournamentsInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MembersInterface::class, Members::class);
        $this->app->bind(TeamsInterface::class, Teams::class);
        $this->app->bind(TournamentsInterface::class, Tournaments::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
