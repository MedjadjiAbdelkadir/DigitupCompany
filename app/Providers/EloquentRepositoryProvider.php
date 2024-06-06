<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Task\EloquentTask;
use App\Repositories\Task\TaskRepository;
use App\Repositories\User\EloquentUser;
use App\Repositories\User\UserRepository;

class EloquentRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, EloquentUser::class);
        $this->app->bind(TaskRepository::class, EloquentTask::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
