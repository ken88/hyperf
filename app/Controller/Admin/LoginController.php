<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/4
 * Time: 13:23
 */

namespace App\Controller\Admin;

use App\Logic\User\UserLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Annotation\AutoController;


/**
 * @AutoController()
 */
/*
 * @AutoController 注解
 * @AutoController 为绝大多数简单的访问场景提供路由绑定支持，
 * 使用 @AutoController 时则 Hyperf 会自动解析所在类的所有 public 方法并提供 GET 和 POST 两种请求方式。
 * 使用 @AutoController 注解时需 use Hyperf\HttpServer\Annotation\AutoController; 命名空间；
 * */
class LoginController
{
    /**
     * @Inject
     * @var UserLogic
     */
    private $userLogic;

    # 登录
    public function sigIn(RequestInterface $request) {

        $username = $request->input('username',null); # 用户名
        $password = $request->input('password',null); # 密码
        if (empty($username) || empty($password)) {
            return [
                'code' => 300,
                'mes' => '用户名或密码错误！'
            ];
        }
        $res = $this->userLogic->sigIn($username,$password);
        if ($res === false) {
            return [
                'code' => 300,
                'mes' => '用户名或密码错误！！！'
            ];
        }
        return [
            'code' => 200,
            'mes' => '登录成功！',
            'data' => $res
        ];

    }

    public function add() {
        return "this is login add ok!";
    }
}