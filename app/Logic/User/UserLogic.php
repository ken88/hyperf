<?php
/**
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/7
 * Time: 11:09
 */

namespace App\Logic\User;

use App\Model\User;
use Hyperf\Utils\ApplicationContext;

use Hyperf\Paginator\Paginator;
use Hyperf\Paginator\LengthAwarePaginator;

class UserLogic
{
    /**
     * 登录检测
     * @param $username 用户名
     * @param $password 密码
     * @return array|false 成功返回用户信息 | 失败false
     */
    public function sigIn($username , $password) {

        # 1. 查询用户信息
        $user = User::query()
            ->select(['username','password','id'])
            ->where('username',$username)
            ->first()
            ->toArray();


        # 2. 检测数据是否正确
        if (!empty($user['password'])) {
            # 3. 检测密码是否正确
            if (md5($password) == $user['password']) {
                $token = md5($user['id'].$user['password'].mt_rand(1000,50000)); # 生成token

                # 4. 保存用户token 放到redis
                $container = ApplicationContext::getContainer();
                $redis = $container->get(\Hyperf\Redis\Redis::class);
                if (!$redis->set('user'.$user['id'],$token,36000)) {
                    return false;
                }

                return [
                    'id' => $user['id'],
                    'token' => $token
                ];
            }
        }
        return false;
    }

    /**
     * 列表
     * @param $currentPage 当前页
     * @param $perPage     每页条数
     * @param array $where 条件
     * @return array       结果集
     */
    public function index(int $currentPage  , int $perPage ,array $where = []) {

        # 查询列
        $columns = ['id','username','created_at'];

        # 条件
//        $where[] = ['id','>',10];
//        $where[] = ['username','=','a5'];

        # 查询构造
        $query = User::query()->where($where);

        # 总条数
        $count = $query->count();

        # 结果集
        $users = $query->select($columns)
            ->forPage($currentPage, $perPage)
            ->get()
            ->toArray();

        return ['list' => $users,'count' => $count];
    }
}