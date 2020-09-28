<?php

namespace App\Providers;

use App\Http\Services\JobFetcher;
use App\Http\Services\JobFetcherImpl;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class JobFetcherServiceProvider extends ServiceProvider {
    public function boot() {}

    public function register() {
        $this->app->bind(
            JobFetcher::class,
            JobFetcherImpl::class
        );
    }
}
