<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', 'Api\AuthController@login');
    $router->post('/register', 'Api\AuthController@register');
    $router->post('sendPasswordResetLink', 'Api\ResetPasswordController@sendEmail');
    $router->post('resetPassword', 'Api\ChangePasswordController@passwordResetProcess');
    $router->group(['middleware' => ['permission:read book|delete book|create book|edit book|update book']], function () use ($router) {
        $router->get('/me', 'Api\AuthController@me');
        $router->get('/logout', 'Api\AuthController@logout');
        $router->post('/refresh', 'Api\AuthController@refresh');
        $router->post('/change', 'Api\AuthController@changePassword');
        $router->get('/book', 'Api\Book\BookController@index');
        $router->post('/create-book', 'Api\Book\BookController@store');
        $router->get('/edit-book/{id}', 'Api\Book\BookController@edit');
        $router->post('/update-book/{id}', 'Api\Book\BookController@update');
        $router->delete('/delete-book/{id}', 'Api\Book\BookController@delete');
    });
});



// Route::group(['middleware' => ['permission:publish articles']], function () {
//     //
// });