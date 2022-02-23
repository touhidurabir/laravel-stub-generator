# Laravel Stub Generator

A php laravel package to generate useable php files from given stub files . 

## Installation

Require the package using composer:

```bash
composer require touhidurabir/laravel-stub-generator
```

## Usage

The best approach to use this via the facade as 

```php
use Touhidurabir\StubGenerator\Facades\StubGenerator
```

and then implement as follow 

```php
StubGenerator::from('some/path/to/stub/file.stub') // the stub file path
    ->to('some/path/to/store/generated/file') // the store directory path
    ->as('TheGeneratingFileNameItself') // the generatable file name without extension 
    ->ext('php') // the file extension(optional, by default to php)
    // ->noExt() // to remove the extension from the file name for the generated file like .env
    ->withReplacers([]) // the stub replacing params
    ->save(); // save the file
```

By default it generate file with **php** extension but possible to override it as 

```php
StubGenerator::from('some/path/to/stub/file.stub') // the stub file path
    ...
    ->ext('ANY_FILE_EXTENSION') 
    ...
    ->save(); // save the file
```

Or set to no extension to generate **.env** like files as 

```php
StubGenerator::from('some/path/to/stub/file.stub') // the stub file path
    ...
    ->noExt() // to remove the extension from the file name for the generated file like .env
    ...
    ->save(); // save the file
```

Also possible to get the generated content as string or download the generated file

```php
StubGenerator::from('path')->to('path')->as('name')->withReplacers([])->toString(); // get generated content
StubGenerator::from('path')->as('name')->withReplacers([])->download(); // download the file
```

By default it assume all the given path for **from** and **to** methods are relative path, but it can also work with absolute path by specifying that. 

```php
StubGenerator::from('some/path/to/stub/file.stub', true) // the second argument **true** specify absolute path
    ->to('some/path/to/store/generated/file', false, true) // the third argument **true** specify absolute path
    ->as('TheGeneratingFileNameItself') 
    ->withReplacers([]) 
    ->save();
```

Also if the store directory path does not exists, it can create that target store path if that is specified in the method call. 

```php
StubGenerator::from('some/path/to/stub/file.stub', true)
    ->to('some/path/to/store/generated/file', true, true) // the second argument **true** specify to generated path if not exists
    ->as('TheGeneratingFileNameItself')
    ->withReplacers([])
    ->save();
```

If the saved generated file aleady exists, it is also possible to replace that with the newly generated one if that is specified via the **replace** method. 

```php
StubGenerator::from('some/path/to/stub/file.stub') // the stub file path
    ->to('some/path/to/store/generated/file') // the store directory path
    ->as('TheGeneratingFileNameItself') // the generatable file name without extension 
    ->withReplacers([]) // the stub replacing params
    ->replace(true) // instruct to replace if already exists at the give path
    ->save(); // save the file
```
One important thing to note that this package can handle not just **string** type values to but also hanlde **array** and **boolean** type also. So basically it can do as :

```php
->withReplacers([
    'replacer_1' = 'some value',
    'replacer_2' = ['some', 'more', 'values'],
    'replacer_3' = true,
])  
```
## Example

Considering the following stub file
 
```stub
<?php

namespace {{classNamespace}};

use {{baseClass}};
use {{modelNamespace}}\{{model}};

class {{class}} extends {{baseClassName}} {

	/**
     * Constructor to bind model to repo
     *
     * @param  object<{{modelNamespace}}\{{model}}> ${{modelInstance}}
     * @return void
     */
    public function __construct({{model}} ${{modelInstance}}) {

        $this->model = ${{modelInstance}};

        $this->modelClass = get_class(${{modelInstance}});
    }

}

```

If the stub file stored at the location of **app/stubs/repository.stub** and we want to create a new repository file named **UserRepository.php** at the path **app/Repositories/**, then

```php
StubGeneratorFacade::from('/app/stubs/repository.stub')
    ->to('/app/Repositories', true)
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
    ->save();
```

will generate such file content of **UserRepository.php**

```php
<?php

namespace App\Repositories;

use Touhidurabir\ModelRepository\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository {

	/**
     * Constructor to bind model to repo
     *
     * @param  object<App\Models\User> $user
     * @return void
     */
    public function __construct(User $user) {

        $this->model = $user;

        $this->modelClass = get_class($user);
    }

}

```

## Extras

Sometimes what we have is the namespace that follows the **psr-4** standeard and that namespace path is what we intend to use for path. This package can direcly work with the namespace path and it includes a handy trait that can help up to some extent. 

To use the **trait**

```php

use Touhidurabir\StubGenerator\Concerns\NamespaceResolver;

class Someclass {

    use NamespaceResolver;
}
```

### Available Methods of **NamespaceResolver**

#### resolveClassName(string $name)

As per defination 

```php
/**
 * Resolve the class name and class store path from give class namespace
 * In case a full class namespace provides, need to extract class name
 *
 * @param  string $name
 * @return string
 */
public function resolveClassName(string $name)
```

It extract the class name from given full class namespace.

#### resolveClassNamespace(string $name)

As per defination 

```php
/**
 * Resolve the class namespace from given class name
 *
 * @param  string $name
 * @return mixed<string|null>
 */
public function resolveClassNamespace(string $name)
```

It extract the class namespace only from given full class namespace.

#### generateFilePathFromNamespace(string $namespace = null)

As per defination 

```php
/**
 * Generate class store path from give namespace
 *
 * @param  mixed<string|null> $namespace
 * @return mixed<string|null>
 */
public function generateFilePathFromNamespace(string $namespace = null)
```

It generate the class relative path from given class full namespace.


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
