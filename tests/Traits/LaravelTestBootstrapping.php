<?php

namespace Touhidurabir\StubGenerator\Tests\Traits;

use Touhidurabir\StubGenerator\Facades\StubGenerator;
use Touhidurabir\StubGenerator\StubGeneratorServiceProvider;

trait LaravelTestBootstrapping {

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app) {

        return [
            StubGeneratorServiceProvider::class,
        ];
    }   
    
    
    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app) {
        
        return [
            'StubGenerator' => StubGenerator::class,
        ];
    }
}