<?php

namespace App\Providers;

use App\Http\Services\JobsFetcher;
use App\Http\Services\JobsFetcherImpl;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class JobsFetcherServiceProvider extends ServiceProvider {
    public function boot() {}

    public function register() {
        $this->app->bind(
            JobsFetcher::class,
            JobsFetcherImpl::class
        );
    }
}
