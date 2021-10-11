<?php
declare(strict_types=1);
/**
 * 企业组
 * Created by PhpStorm.
 * User: author yukai
 * Date: 2021/9/9
 * Time: 14:01
 */

namespace App\Controller;

use App\Logic\EnterpriseLogic;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;

/**
 * @AutoController
 */
class EnterpriseController extends AbstractController
{
    /**
     * @Inject
     * @var EnterpriseLogic
     */
    private $enterpriseLogic;

    # 列表
    public function index() {

        # 1. 接受参数信息
        $currentPage = (int) $this->request->input('currentPage', 1);       # 页码
        $perPage     = (int) $this->request->input('perPage', 15);          # 每页的条数
        $enterprise_name = $this->request->input('enterprise_name', '');    # 企业组名字

        # 2. 搜索条件
        $where = [];
        if (!empty($enterprise_name)) {
            $where[] = ['enterprise_name','=',$enterprise_name];
        }

        # 3. 获取数据
        $item = $this->enterpriseLogic->index($currentPage,$perPage,$where);
        return $this->res(200,'',$item['list'],['count'=>$item['count']]);
    }

    # 新增
    public function add() {

        # 1. 接受参数信息
        $enterprise_name = $this->request->input('enterprise_name',''); # 企业组名字
        $userId = $this->request->input('userId',0); # 用户id

        # 2. 数据验证
        if (empty($enterprise_name)) {
           return $this->res(300,'企业名不能为空！');
        }

        # 3. 调用Logic方法操作
        $item = $this->enterpriseLogic->add($enterprise_name,$userId);

        # 4. 返回值验证
        $code = 200;
        $mes = '';
        if ($item === -1) {
            $code = 300;
            $mes = '企业组已存在!';
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
        $id = $this->request->input('enterprise_id',0); # 企业组id
        $enterprise_name = $this->request->input('enterprise_name',''); # 企业组名字

        # 2. 数据验证
        if (empty($enterprise_name)) {
            return $this->res(300,'企业名不能为空！');
        }

        # 3. 调用Logic方法操作
        $item = $this->enterpriseLogic->edit($id,$enterprise_name);

        # 4. 返回值验证
        $code = 200;
        $mes = '';
        if ($item === -1) {
            $code = 300;
            $mes = '企业组已存在!';
        } else if ($item) {
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
        $id = $this->request->input('enterprise_id',0); # 企业组id

        # 2. 调用Logic方法操作
        $item = $this->enterpriseLogic->del($id);

        # 3. 返回值验证
        if ($item) {
            return $this->res(200,'操作成功！');
        }
        return $this->res(300,'操作失败！');
    }
}