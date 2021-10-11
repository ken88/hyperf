<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Hyperf\Utils\ApplicationContext;

class FooMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        # 1. 检测是否是登录接口，登录接口直接返回
        if ($request->getRequestTarget() == '/admin/login/sigIn') {
            return $handler->handle($request);
        }

        # 2. token验证
        $userId = $this->request->post('userId','0');
        $token  = $this->request->post('token','');

        if (empty($userId) || empty($token)) {
            return $this->response->json(
                [
                    'code' => -1001,
                    'mes' => '参数错误,缺少参数,用户验证失败',
                ]
            );
        }

        $container = ApplicationContext::getContainer();
        $redis = $container->get(\Hyperf\Redis\Redis::class);
        $redisToken = $redis->get("user{$userId}");
        if (!$redisToken || $redisToken != $token) {
            return $this->response->json(
                [
                    'code' => -1002,
                    'mes' => '登录失效，请重新登录!',
                ]
            );
        }

        return $handler->handle($request);

    }
}