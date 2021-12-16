<?php

namespace Touhidurabir\StubGenerator\Test;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Touhidurabir\StubGenerator\StubFactory;
use Touhidurabir\StubGenerator\Tests\Traits\LaravelTestBootstrapping;

class StubFactoryTest extends TestCase {

    use LaravelTestBootstrapping;
    
    /**
     * @test
     */
    public function the_stub_factory_can_be_initialted() {

        $stubFactory = new StubFactory;

        $this->assertTrue($stubFactory instanceof StubFactory);
        $this->assertIsObject($stubFactory);
    }


    /**
     * @test
     */
    public function the_stub_factory_will_return_content_on_provided_stub() {

        $stubFactory = new StubFactory;
        
        $this->assertIsString($stubFactory->make(__DIR__ . "/stubs/repository.stub", []));
    }


    /**
     * @test
     */
    public function the_stub_factory_will_return_generated_content_based_on_provided_stub() {

        $stubFactory = new StubFactory;

        $generatedContent = $stubFactory->make(__DIR__ . "/stubs/repository.stub", [
            'class'             => 'ExampleRepository',
            'model'             => 'Example',
            'modelInstance'     => 'example',
            'modelNamespace'    => 'App\\Models',
            'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
            'baseClassName'     => 'BaseRepository',
            'classNamespace'    => 'App\\Repositories',
        ]);
        
        $this->assertIsString($generatedContent);

        $this->assertEquals(
            trim($generatedContent),
            trim(File::get(__DIR__ . "/App/Repositories/ExampleRepository.php"))
        );
    }


    /**
     * @test
     */
    public function the_stub_factory_getStubContent_return_indetical_content_from_given_stub_file() {
        
        $stubFactory = new StubFactory;

        $stubFactory->make(__DIR__ . "/stubs/repository.stub", []);

        $this->assertIsString($stubFactory->getStubContent());

        $this->assertEquals($stubFactory->getStubContent(), File::get(__DIR__ . "/stubs/repository.stub"));
    }


    /**
     * @test
     */
    public function the_stub_factory_getGeneratedContent_return_indetical_content_of_generated_content() {
        
        $stubFactory = new StubFactory;

        $generatedContent = $stubFactory->make(__DIR__ . "/stubs/repository.stub", [
            'class'             => 'UserRepository',
            'model'             => 'User',
            'modelInstance'     => 'user',
            'modelNamespace'    => 'App\\Models',
            'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
            'baseClassName'     => 'BaseRepository',
            'classNamespace'    => 'App\\Repositories',
        ]);
        
        $this->assertIsString($stubFactory->getGeneratedContent());
        
        $this->assertEquals($stubFactory->getGeneratedContent(), $generatedContent);       
    }
}