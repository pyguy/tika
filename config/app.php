<?php

/**
 * @author Alireza Josheghani <josheghani.dev@gmail.com>
 * @package Lemax
 * @verison 1.2
 * ---------------------------------------------------
 * The application configure file
 * ---------------------------------------------------
 */

return [

    // set calendar timezone
    'timezone' => 'Asia/Tehran',

    // application language
    'lang' => 'en',

    // jallali date config : true | false
    'jalali' => true,

    'namespaces' => [
        
        // the controllers namespace
        'controllers' => 'App\\Http\\Controllers',

        // the application commands namespace
        'commands' => 'App\\Console',

        // the database migrations namespace
        'migrations' => 'Database\\Migrations',

        // the database seeds namespace
        'seeds' => 'Database\\Seeds',

    ],

    // Register your commands
    'console' => [
        // App\Console\TestCommand::class,
    ],

];