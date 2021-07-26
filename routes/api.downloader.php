<?php

/** @var \Laravel\Lumen\Routing\Router|\Illuminate\Routing\Router $router */

$router->group(['prefix' => 'api', 'middleware' => []], function () use ($router) {
    $router->get(
        '/resource',
        [
            'uses' => '\App\LaravelDownloaderAPI\Http\Controllers\FetchResourceController@fetch'
        ]
    );
});
