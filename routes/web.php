<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\BookController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->group(['prefix'=>'api'],function($router){
//     $router->get('/test', 'BookController@index');
// });

$router->group(['prefix'=>'admin','middleware' => 'auth:api'], function ($router) {
 
});

// $router->get('/book', 'BookController@index');
// $router->post('/book/create', 'BookController@create');
// $router->put('/book/update/{id}', 'BookController@update');
$router->get('/category', 'CategoryController@index');
$router->post('/category/create', 'CategoryController@create');
$router->put('/category/update/{id}', 'CategoryController@update');
$router->delete('/category/delete/{id}', 'CategoryController@delete');




$router->get('/book', 'BookController@index');
$router->post('/book/create', 'BookController@create');
$router->get('/book/{id}/restore', 'BookController@restore');
$router->put('/book/update/{id}', 'BookController@update');
$router->get('/book/search', 'BookController@search');
$router->delete('/book/delete/{id}', 'BookController@delete');
