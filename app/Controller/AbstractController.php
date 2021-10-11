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
namespace App\Controller;

use _PHPStan_0ebfea013\Symfony\Component\Console\Exception\LogicException;
use App\Exception\FooException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

use PharIo\Manifest\InvalidApplicationNameException;
use Psr\Container\ContainerInterface;


abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param int $code  状态码
     * @param string $message 说明
     * @param array $data 返回结果集
     * @param array $info 其他信息 例如分页等
     */
    public function res(int $code = 200, string $message = '', array $data = [],array $info = []) {
        $res = [
            'code' => $code,
            'mes' => $message,
            'data' => $data,
            'info' => $info
        ];
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }
}
