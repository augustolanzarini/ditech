<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::get('sala', [
    'middleware' => 'auth',
    'uses' => 'SalaController@index'
]);

Route::post('newSala', [
    'middleware' => 'auth',
    'uses' => 'SalaController@newSala'
]);

Route::post('newUpdate', [
    'middleware' => 'auth',
    'uses' => 'SalaController@newUpdate'
]);

Route::post('getUpdate', [
    'middleware' => 'auth',
    'uses' => 'SalaController@getUpdate'
]);

Route::post('deleteSala', [
    'middleware' => 'auth',
    'uses' => 'SalaController@deleteSala'
]);