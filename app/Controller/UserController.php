<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/4
 * Time: 16:34
 */

namespace App\Controller;

use App\Logic\User\UserLogic;
use Hyperf\Di\Annotation\Inject;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;
use App\Model\User;

/**
 * @AutoController()
 */
class UserController extends AbstractController
{
    /**
     * @Inject
     * @var UserLogic
     */
    private $userLogic;

    public function index() {

        $currentPage = (int) $this->request->input('currentPage', 1); # 页码
        $perPage     = (int) $this->request->input('perPage', 15);    # 每页的条数

        $item = $this->userLogic->index($currentPage,$perPage);

        return $this->res(200,'',$item['list'],['count'=>$item['count']]);
    }


    public function addUser() {
        $username = $this->request->input('username','test');
        $password = $this->request->input('password','123456');
        $user = new User();
        $user->username = $username;
        $user->password = md5($password);

        if ($user->save()){
            return $this->res(200,'录入成功！');
        }
        return $this->res(300,'录入失败');
    }

    public function updateQuery() {
        $affected = Db::update('UPDATE user set username = ? WHERE id = ?', ['李四', 3]); // 返回受影响的行数 int
        return $affected;
    }

    public function modelIndex() {
//        $user = User::query()->where('id',9)->first();

        $user = User::query()->where('id','>',4)->get()->toArray();
        var_dump($user);

    }

    public function modelUpdate() {
       $user = User::query()->where('id',2)->first();
       $user->user_info = "hello wordls";
       return $user->save();
    }
}