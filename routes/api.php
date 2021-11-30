<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'v1', 'namespace' => 'v1'],function () use($router){
    $router->get('/courses','CourseController@index');
});

