<?php

namespace Touhidurabir\StubGenerator\Tests;

use Exception;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Touhidurabir\StubGenerator\StubGenerator;
use Touhidurabir\StubGenerator\Tests\Traits\LaravelTestBootstrapping;
use Touhidurabir\StubGenerator\Facades\StubGenerator as StubGeneratorFacade;

class StubGeneratorTest extends TestCase
{
    use LaravelTestBootstrapping;

    /**
     * The non removeable repository files
     *
     * @var array
     */
    protected $cleanUpExcludeFileNames = [
        'ExampleRepository.php',
        'ExamplesTableSeeder.php',
    ];


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {

        parent::setUp();

        $self = $this;

        $this->beforeApplicationDestroyed(function () use ($self) {

            foreach (glob(__DIR__ . '/App/Repositories/*.*') as $fileFullPath) {

                if (! in_array(last(explode('/', $fileFullPath)), $self->cleanUpExcludeFileNames)) {

                    File::delete($fileFullPath);
                }
            }

            foreach (glob(__DIR__ . '/App/Seeders/*.*') as $fileFullPath) {

                if (! in_array(last(explode('/', $fileFullPath)), $self->cleanUpExcludeFileNames)) {

                    File::delete($fileFullPath);
                }
            }

            if (File::isDirectory(__DIR__ . '/App/Repositories/Extras')) {

                array_map('unlink', glob(__DIR__ . '/App/Repositories/Extras/*.*'));

                $dir    = __DIR__ . '/App/Repositories/Extras';
                $extras = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                $extras = new RecursiveIteratorIterator($extras, RecursiveIteratorIterator::CHILD_FIRST);

                foreach ($extras as $file) {

                    $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
                }

                rmdir($dir);
            }
        });
    }


    public function test_the_stub_generator_can_be_initialted()
    {

        $stubGenerator = new StubGenerator();

        $this->assertTrue($stubGenerator instanceof StubGenerator);
        $this->assertIsObject($stubGenerator);
    }


    public function test_the_stub_generator_can_be_initialted_from_facade()
    {

        $stubGenerator = StubGeneratorFacade::getFacadeRoot();

        $this->assertTrue($stubGenerator instanceof StubGenerator);
        $this->assertIsObject($stubGenerator);
    }


    public function test_the_package_registers_the_stub_generator_facade_alias()
    {

        $composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);

        $aliases = $composer['extra']['laravel']['aliases'];

        $this->assertSame(StubGeneratorFacade::class, $aliases['StubGenerator'] ?? null);

        // the old copy-pasted alias stays until the next major release
        $this->assertSame(StubGeneratorFacade::class, $aliases['ModelRepository'] ?? null);
    }


    public function test_giving_invalid_stub_path_will_throw_exception()
    {

        $this->expectException(Exception::class);

        $stubGenerator = StubGeneratorFacade::from('stubs/repository-non-existing.stub');
    }


    public function test_giving_valid_stub_path_will_work_and_return_class_instance()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true);

        $this->assertTrue($stubGenerator instanceof StubGenerator);
        $this->assertIsObject($stubGenerator);
    }


    public function test_giving_invalid_store_directory_path_will_throw_exception()
    {

        $this->expectException(Exception::class);

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/some/folder/path', false, true);
    }


    public function test_giving_valid_store_directory_apth_will_work_and_return_class_instance()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories');

        $this->assertTrue($stubGenerator instanceof StubGenerator);
        $this->assertIsObject($stubGenerator);
    }


    public function test_will_throw_exception_if_given_file_name_already_exists()
    {

        $this->expectException(Exception::class);

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories')
                                            ->as('ExampleRepository')
                                            ->save();
    }


    public function test_will_return_true_on_given_file_already_exists_if_allow_replace()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories')
                                            ->as('UserRepository')
                                            ->withReplacers([
                                                'class'             => 'UserRepository',
                                                'model'             => 'User',
                                                'modelInstance'     => 'user',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true)
                                            ->save();

        $this->assertTrue($stubGenerator);
    }


    public function test_will_return_generate_proper_file_via_stub()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories')
                                            ->as('ProfileRepository')
                                            ->withReplacers([
                                                'class'             => 'ProfileRepository',
                                                'model'             => 'Profile',
                                                'modelInstance'     => 'profile',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true)
                                            ->save();

        $this->assertTrue($stubGenerator);
    }


    public function test_it_will_generate_store_directory_when_not_exists_if_asked_to()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories/Extras', true, true)
                                            ->as('ExtraRepository')
                                            ->withReplacers([
                                                'class'             => 'ExtraRepository',
                                                'model'             => 'Extra',
                                                'modelInstance'     => 'extra',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true);

        $content = $stubGenerator->toString();
        $storeFile = $stubGenerator->save();

        $this->assertTrue($storeFile);
        $this->assertTrue(File::exists(__DIR__ . '/App/Repositories/Extras/ExtraRepository.php'));
        $this->assertEquals($content, File::get(__DIR__ . '/App/Repositories/Extras/ExtraRepository.php'));
    }


    public function test_it_can_generate_file_with_different_extensions()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories/Extras', true, true)
                                            ->as('ExtraRepository')
                                            ->ext('yaml')
                                            ->withReplacers([
                                                'class'             => 'ExtraRepository',
                                                'model'             => 'Extra',
                                                'modelInstance'     => 'extra',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true);

        $content = $stubGenerator->toString();
        $storeFile = $stubGenerator->save();

        $this->assertTrue($storeFile);
        $this->assertTrue(File::exists(__DIR__ . '/App/Repositories/Extras/ExtraRepository.yaml'));
        $this->assertEquals($content, File::get(__DIR__ . '/App/Repositories/Extras/ExtraRepository.yaml'));
    }

    public function test_it_can_generate_file_with_no_extensions()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories/Extras', true, true)
                                            ->as('ExtraRepository')
                                            ->noExt()
                                            ->withReplacers([
                                                'class'             => 'ExtraRepository',
                                                'model'             => 'Extra',
                                                'modelInstance'     => 'extra',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true);

        $content = $stubGenerator->toString();
        $storeFile = $stubGenerator->save();

        $this->assertTrue($storeFile);
        $this->assertTrue(File::exists(__DIR__ . '/App/Repositories/Extras/ExtraRepository'));
        $this->assertEquals($content, File::get(__DIR__ . '/App/Repositories/Extras/ExtraRepository'));
    }


    public function test_it_can_return_generate_file_content_as_string()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories')
                                            ->as('ProfileRepository')
                                            ->withReplacers([
                                                'class'             => 'ProfileRepository',
                                                'model'             => 'Profile',
                                                'modelInstance'     => 'profile',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true);

        $content = $stubGenerator->toString();
        $stubGenerator->save();

        $this->assertIsString($content);
        $this->assertEquals($content, File::get(__DIR__ . '/App/Repositories/ProfileRepository.php'));
    }


    public function test_it_can_download_file_of_given_name_with_generated_content_based_on_stub()
    {

        $stubGenerator = StubGeneratorFacade::from(__DIR__ . '/stubs/repository.stub', true)
                                            ->to(__DIR__ . '/App/Repositories')
                                            ->as('TestRepository')
                                            ->withReplacers([
                                                'class'             => 'TestRepository',
                                                'model'             => 'Test',
                                                'modelInstance'     => 'test',
                                                'modelNamespace'    => 'App\\Models',
                                                'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
                                                'baseClassName'     => 'BaseRepository',
                                                'classNamespace'    => 'App\\Repositories',
                                            ])
                                            ->replace(true)
                                            ->download();

        $this->assertEquals($stubGenerator->getStatusCode(), 200);
    }


    public function test_download_sends_the_file_name_with_its_extension()
    {

        $response = StubGeneratorFacade::from(__DIR__ . '/stubs/seeder.stub', true)
                                        ->as('TestsTableSeeder')
                                        ->download();

        $this->assertSame(
            'attachment; filename=TestsTableSeeder.php',
            $response->headers->get('Content-Disposition')
        );
    }


    public function test_download_without_extension_has_no_trailing_dot_in_file_name()
    {

        $response = StubGeneratorFacade::from(__DIR__ . '/stubs/seeder.stub', true)
                                        ->as('.env')
                                        ->noExt()
                                        ->download();

        $this->assertSame(
            'attachment; filename=.env',
            $response->headers->get('Content-Disposition')
        );
    }


    public function test_download_quotes_file_name_when_needed()
    {

        $response = StubGeneratorFacade::from(__DIR__ . '/stubs/seeder.stub', true)
                                        ->as('Tests Table Seeder')
                                        ->ext('txt')
                                        ->download();

        $this->assertSame(
            'attachment; filename="Tests Table Seeder.txt"',
            $response->headers->get('Content-Disposition')
        );
    }


    public function test_it_will_generate_file_with_proper_matched_contant_as_expected()
    {

        $content = StubGeneratorFacade::from(__DIR__ . '/stubs/seeder.stub', true)
                                        ->to(__DIR__ . '/App/Seeders')
                                        ->as('TestsTableSeeder')
                                        ->withReplacers([
                                            'class'         => 'ExamplesTableSeeder',
                                            'table'         => 'examples',
                                            'ignorables'    => ['id', 'deleted_at'],
                                            'useables'      => [],
                                            'timestamp'     => true,
                                        ])
                                        ->toString();

        $this->assertEquals(
            trim($content),
            trim(File::get(__DIR__ . '/App/Seeders/ExamplesTableSeeder.php'))
        );
    }

}
