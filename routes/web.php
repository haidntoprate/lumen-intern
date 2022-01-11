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
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/me', 'Api\AuthController@me');
        $router->get('/logout', 'Api\AuthController@logout');
        $router->post('/refresh', 'Api\AuthController@refresh');
        $router->post('/change', 'Api\AuthController@changePassword');
    });
});
$router->get('/getuser', 'Api\AuthController@getUser');