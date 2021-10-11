<?php
declare(strict_types=1);
/**
 * 业务
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/9
 * Time: 16:38
 */

namespace App\Controller;

use App\Logic\BusinessLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;


/**
 * @AutoController()
 */

class BusinessController extends AbstractController
{

    /**
     * @Inject
     * @var BusinessLogic
     */
    private $businessLogic;

    # 列表
    public function index() {

        # 1. 接受参数信息
        $currentPage = (int) $this->request->input('currentPage', 1);   # 页码
        $perPage     = (int) $this->request->input('perPage', 15);      # 每页的条数
        $business_name = $this->request->input('business_name', '');    # 业务名

        # 2. 搜索条件
        $where = [];
        if (!empty($business_name)) {
            $where[] = ['business_name','=',$business_name];
        }

        # 3. 获取数据
        $item = $this->businessLogic->index($currentPage,$perPage,$where);
        return $this->res(200,'',$item['list'],['count'=>$item['count']]);
    }

    # 新增
    public function add() {

        # 1. 接受参数信息
        $business_name = $this->request->input('business_name',''); # 业务名
        $is_status = (int)$this->request->input('is_status',0);     # 状态
        $userId = (int)$this->request->input('userId',0);           # 用户id

        # 2. 数据验证
        if (empty($business_name)) {

            return $this->res(300,'业务名不能为空！');

        } else if ($is_status < 1 || $is_status > 3) {

            return $this->res(300,'状态不正确！');
        }

        # 3. 调用Logic方法操作
        $item = $this->businessLogic->add($business_name,$userId,$is_status);

        # 4. 返回值验证
        $code = 200;
        $mes = '';
        if ($item === -1) {
            $code = 300;
            $mes = '业务名已存在!';
        } else if ($item) {
            $mes = '操作成功!';
        } else {
            $code = 300;
            $mes = '操作失败!';
        }

        return $this->res($code,$mes);
    }

    # 编辑
    public function edit() {

        # 1. 接受参数信息
        $id = (int)$this->request->input('business_id',0);          # 业务id
        $is_status = (int)$this->request->input('is_status',0);     # 状态
        $business_name = $this->request->input('business_name',''); # 业务名字

        # 2. 数据验证
        if (empty($business_name)) {
            return $this->res(300,'业务名不能为空！');
        }else if ($is_status < 1 || $is_status > 3) {

            return $this->res(300,'状态不正确！');
        }

        # 3. 调用Logic方法操作
        $item = $this->businessLogic->edit($id,$business_name,$is_status);

        # 4. 返回值验证
        $code = 200;
        $mes = '';
        if ($item) {
            $mes = '操作成功!';
        } else {
            $code = 300;
            $mes = '操作失败!';
        }

        return $this->res($code,$mes);
    }

    # 删除
    public function del() {

        # 1. 接受参数信息
        $id = $this->request->input('business_id',0); # 业务id

        # 2. 调用Logic方法操作
        $item = $this->businessLogic->del($id);

        # 3. 返回值验证
        if ($item) {
            return $this->res(200,'操作成功！');
        }
        return $this->res(300,'操作失败！');
    }
}