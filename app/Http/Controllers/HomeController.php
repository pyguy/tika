<?php

/**
 * @package App\Http\Controllers
 * @name HomeController
 * @created_at 2016/09/16 01:21 AM
 * This file was generated with lemax command-line
 */

namespace App\Http\Controllers;
use Lemax\Foundation\BaseController;

class HomeController extends BaseController {

    public function index()
    {
        view('pages.home');
    }

    public function login()
    {
        view('pages.login');
    }
    
}