<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/logviewer'], function (Router $router) {
    $router->get('/', [
        'as' => 'admin.logviewer.logviewer.index',
        'uses' => 'LogviewerController@index',
        'middleware' => 'can:logviewer.logviewers.index'
    ]);
// append

});
