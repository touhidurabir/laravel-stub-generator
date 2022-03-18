<?php

namespace Database\Seeders;

use Touhidurabir\SeedExtender\BaseTableSeeder;

class ExamplesTableSeeder extends BaseTableSeeder {
    
    /**
     * Seeder table name 
     *
     * @var string
     */
    protected $table = "examples";


    /**
     * The table attributes/columns that will be ignored during the seeding process
     *
     * @var array
     */
    protected $ignorables = ["id", "deleted_at"];


    /**
     * The table attributes/columns that will be used during the seeding process
     *
     * @var array
     */
    protected $useables = [];


    /**
     * Should merge and include timestamps[created_at, updated_at] by default into the seed data
     *
     * @var boolean
     */    
    protected $includeTimestampsOnSeeding = true;

    
    /**
     * The seeding data
     *
     * @var array
     */
    protected $data = [
    	
    ];


    /**
     * Build up the seedeable data set;
     *
     * @return array
     */
    protected function seedableDataBuilder() {

        foreach ($this->data as $key => $value) {
            
            $this->data[$key] = array_merge($value, [

            ]);
        }

        return $this->data;
    }
}
