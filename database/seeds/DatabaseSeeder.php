<?php

/**
 * @author Alireza Josheghani <josheghani.dev@gmail.com>
 * <<<<<< Run the database seeder >>>>>>
 * You can call a database seeder class
 * with the " run " method like the example
 */

namespace Database\Seeds;
use Lemax\Foundation\BaseSeeder;

class DatabaseSeeder extends BaseSeeder {

    public function run()
    {
        $this->call(UserTableSeeder::class);
    }
    
}