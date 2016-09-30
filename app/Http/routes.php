<?php

namespace App\Http;
use Lemax\Http\Route;

// Web Route-s
Route::get('/','HomeController@index');
//Route::get('/login','HomeController@login');
//Route::post('/login','AuthController@login');
//Route::get('/logout','AuthController@logout');

// Client Route-s
Route::get('/check/cpu','ClientController@cpu');
Route::get('/check/gateway','ClientController@gatewayIpAddr');
Route::get('/check/traceroute','ClientController@traceRouteHost');
Route::get('/check/nameserver','ClientController@nameServer');
Route::get('/check/packet_loss','ClientController@packet_loss');
Route::get('/check/pppoe_status','ClientController@PppoeStatus');
Route::get('/check/memory','ClientController@memory');
Route::get('/check/disk','ClientController@disk');
Route::get('/check/network','ClientController@network');
Route::get('/check/ping','ClientController@ping');
Route::get('/check/last_login','ClientController@last_login');
Route::get('/check/load_average','ClientController@load_average');
Route::get('/check/services','ClientController@services');
Route::get('/check/swap','ClientController@swap');
Route::get('/check/system','ClientController@system');
