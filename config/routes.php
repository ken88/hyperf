<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/index/index', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

# test
//Router::addRoute(['GET', 'POST', 'HEAD'], '/test/index', 'App\Controller\TestController@index');
//Router::addRoute(['GET', 'POST', 'HEAD'], '/test/add', 'App\Controller\TestController@add');

