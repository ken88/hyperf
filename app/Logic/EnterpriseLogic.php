<?php
/**
 * 企业组业务
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/9
 * Time: 14:03
 */

namespace App\Logic;

use App\Model\EnterpriseGroup;
use App\Model\User;

class EnterpriseLogic
{
    /**
     * 列表
     * @param $currentPage 当前页
     * @param $perPage     每页条数
     * @param $where       条件
     * @return array       结果集
     */
    public function index( $currentPage, $perPage , $where = []) {

        # 查询列
        $columns = ['id','enterprise_name','add_user','created_at','updated_at'];

        # 查询构造
        $query = EnterpriseGroup::query()->where($where);

        # 总条数
        $count = $query->count();

        # 结果集
        $list = $query->select($columns)
            ->forPage($currentPage, $perPage)
            ->orderBy('id','desc')
            ->get()
            ->toArray();

        return ['list' => $list,'count' => $count];

    }

    /**
     * 新增
     * @param $enterprise_name  企业名
     * @param $userId   用户id
     * @return bool|int 结果集 -1 已存在
     */
    public function add($enterprise_name, $userId) {

        # 1. 查询组名是否存在
        $count = EnterpriseGroup::query()->where('enterprise_name','=',$enterprise_name)->count();
        if ($count > 0) {
            return -1;
        }
        $username = User::query()->find($userId)->value('username'); # 组名

        # 2. 新增保存
        $enterprise = new EnterpriseGroup();
        $enterprise->enterprise_name = $enterprise_name;
        $enterprise->add_user = $username;
        return $enterprise->save();
    }

    /**
     * 修改
     * @param $id  企业id
     * @param $enterprise_name  企业名
     * @return bool|int 结果集 -1 已存在
     */
    public function edit($id,$enterprise_name) {

        # 1. 查询组名是否存在
        $count = EnterpriseGroup::query()
            ->where([
                ['enterprise_name','=',$enterprise_name],
                ['id','!=',$id]
        ])->count();
        if ($count > 0) {
            return -1;
        }

        # 2. 编辑保存
        $enterprise = EnterpriseGroup::query()->find($id);
        $enterprise->enterprise_name = $enterprise_name;
        return $enterprise->save();
    }

    /**
     * 删除
     * @param $id 企业组id
     * @return false|int|mixed
     */
    public function del($id) {
        return EnterpriseGroup::query()->where('id',$id)->delete();
    }
}