<?php

namespace Touhidurabir\StubGenerator;

use Illuminate\Support\ServiceProvider;
use Touhidurabir\StubGenerator\StubGenerator;

class StubGeneratorServiceProvider extends ServiceProvider {
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

    }

    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $this->app->bind('stub-generator', function () {

            return new StubGenerator;
        });
    }
}