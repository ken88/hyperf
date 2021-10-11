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
return [
    'http' => [
        # 检测用户token 合法性中间件
        App\Middleware\Auth\FooMiddleware::class,
        # 验证器 中间件
//        \Hyperf\Validation\Middleware\ValidationMiddleware::class
    ],
];
