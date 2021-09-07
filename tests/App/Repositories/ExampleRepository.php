<?php

namespace App\Repositories;

use Touhidurabir\ModelRepository\BaseRepository;
use App\Models\Example;

class ExampleRepository extends BaseRepository {

	/**
     * Constructor to bind model to repo
     *
     * @param  object<App\Models\Example> $example
     * @return void
     */
    public function __construct(Example $example) {

        $this->model = $example;

        $this->modelClass = get_class($example);
    }

}
