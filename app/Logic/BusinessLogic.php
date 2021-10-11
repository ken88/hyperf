<?php
/**
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/9
 * Time: 16:40
 */

namespace App\Logic;

use App\Model\Business;
use App\Model\User;

class BusinessLogic
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
        $columns = ['id','business_name','is_status','add_user','created_at','updated_at'];

        # 查询构造
        $query = Business::query()->where($where);

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
     * @param $business_name 业务名
     * @param $userId        用户id
     * @param $is_status     状态
     * @return bool|int      结果集
     */
    public function add($business_name, $userId,$is_status) {

        # 1. 查询组名是否存在
        $count = Business::query()
            ->where([
               ['business_name','=',$business_name],
               ['is_status','=',$is_status]
            ])
            ->count();
        if ($count > 0) {
            return -1;
        }
        $username = User::query()->find($userId)->value('username'); # 组名

        # 2. 新增保存
        $business = new Business();
        $business->business_name = $business_name;
        $business->add_user = $username;
        $business->is_status = $is_status;
        return $business->save();
    }

    /**
     * 编辑
     * @param $id 物业id
     * @param $business_name 业务名
     * @param $is_status 状态
     * @return mixed 结果集
     */
    public function edit($id,$business_name,$is_status) {
        # 1. 编辑保存
        $business = Business::query()->find($id);
        $business->business_name = $business_name;
        $business->is_status = $is_status;
        return $business->save();
    }

    /**
     * 删除
     * @param $id 业务id
     * @return false|int|mixed
     */
    public function del($id) {
        return Business::query()->where('id',$id)->delete();
    }
}