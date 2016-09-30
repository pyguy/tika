<?php

/**
 * @author Alireza Josheghani <josheghani.dev@gmail.com>
 * @version 1.2
 * @package Lemax framework application
 */

use Lemax\Foundation\Application;

// Define the application
$app = new Application(
    realpath(__DIR__.'/../')
);

// Run the application
return $app;