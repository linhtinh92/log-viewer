<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/logviewer', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    $router->get('/get-data', [
        'as' => 'api.logviewer.logviewer.index',
        'uses' => 'LogviewerController@index',
        'middleware' => 'can:logviewer.logviewers.index'
    ]);
});
