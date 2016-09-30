<?php

/**
 * @author Alireza Josheghani <josheghani.dev@gmail.com>
 * @package Lemax
 * @verison 1.2
 * ---------------------------------------------------
 * The database configure file
 * ---------------------------------------------------
 */

return [

    /* Database type */
    'database_type' => 'mysql',

    /* Database Name */
    'database_name' => 'tika',

    /* Database Host */
    'server' => 'localhost',

    /* Database User */
    'username' => 'root',

    /* Database Pass */
    'password' => '',

    /* Database Charset */
    'charset' => 'utf8',

    /* Database Option */
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]

];