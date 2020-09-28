<?php

namespace App\Providers;

use App\Http\Repository\JobsRepository;
use App\Http\Repository\JobsRepositoryImpl;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class JobsRepositoryServiceProvider extends ServiceProvider {
    public function boot() {}

    public function register() {
        $this->app->bind(
            JobsRepository::class,
            JobsRepositoryImpl::class
        );
    }
}
