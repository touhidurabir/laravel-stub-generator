<?php

namespace Touhidurabir\StubGenerator\Test;

use PHPUnit\Framework\TestCase;
use Touhidurabir\StubGenerator\Concerns\NamespaceResolver;

class NamespaceResolverTest extends TestCase {

    /**
     * The anonymous test class instance
     * 
     * @var object
     */
    protected $anonymousTestClassObject;


    /**
     * This method is called before each test.
     * 
     * @return void
     */
    protected function setUp(): void {

        $this->anonymousTestClassObject = new class {

            use NamespaceResolver;
        };
    }
    
    /**
     * @test
     */
    public function it_extras_and_returns_proper_class_name_from_given_class_namespace() {

        $className = $this->anonymousTestClassObject->resolveClassName('App\\SomeNameSpace\\SomeClass');

        $this->assertIsString($className);
        $this->assertEquals($className, 'SomeClass');
        $this->assertEquals($this->anonymousTestClassObject->resolveClassName('SomeClass'), 'SomeClass');
    }


    /**
     * @test
     */
    public function it_extras_and_returns_proper_class_namespace_or_null_from_given_fill_class_namespace_path() {

        $className = $this->anonymousTestClassObject->resolveClassNamespace('App\\SomeNameSpace\\SomeClass');

        $this->assertIsString($className);
        $this->assertEquals($className, 'App\\SomeNameSpace');
        $this->assertNull($this->anonymousTestClassObject->resolveClassNamespace('SomeClass'));
    }


    /**
     * @test
     */
    public function it_can_generate_file_relative_path_from_given_namespace() {

        $className = $this->anonymousTestClassObject->generateFilePathFromNamespace('App\\SomeNameSpace\\SomeClass');

        $this->assertIsString($className);
        $this->assertEquals($className, '/App/SomeNameSpace/SomeClass');
        $this->assertEquals($this->anonymousTestClassObject->generateFilePathFromNamespace('SomeClass'), '/SomeClass');
    }
}