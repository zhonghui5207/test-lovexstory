<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------
namespace app\adminapi\controller\course;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\course\CourseCatalogueLogic;
use app\adminapi\validate\course\CourseCatalogueValidate;

/**
 * 课程目录控制类
 * Class CourseCatalogue
 * @package app\adminapi\controller\course
 */
class CourseCatalogueController extends BaseAdminController
{


    /**
     * @notes 列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/23 9:44
     */
    public function lists()
    {
        return $this->dataLists();
    }


    /**
     * @notes 新增目录
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/23 12:01
     */
    public function add()
    {
        $params = (new CourseCatalogueValidate())->post()->goCheck('add');
        (new CourseCatalogueLogic())->add($params);
        return $this->success('添加成功');
    }

    /**
     * @notes 编辑目录
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/23 12:02
     */
    public function edit()
    {
        $params = (new CourseCatalogueValidate())->post()->goCheck('edit');
        (new CourseCatalogueLogic())->edit($params);
        return $this->success('修改成功');
    }

    /**
     * @notes 获取目录详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/23 15:54
     */
    public function detail()
    {
        $params = (new CourseCatalogueValidate())->goCheck('detail');
        $detail = (new CourseCatalogueLogic())->detail($params['id']);
        return $this->success('',$detail);
    }

    /**
     * @notes 删除目录
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/23 12:04
     */
    public function del()
    {
        $params = (new CourseCatalogueValidate())->post()->goCheck('del');
        (new CourseCatalogueLogic())->del($params['id']);
        return $this->success('删除成功');
    }

    /**
     * @notes 修改收费类型
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/7/1 15:15
     */
    public function changeFeeType()
    {
        $params = (new CourseCatalogueValidate())->post()->goCheck('fee');
        $result = (new CourseCatalogueLogic())->changeFeeType($params);
        if(true === $result){
            return $this->success('修改成功');
        }
        return $this->fail($result);
    }


    /**
     * @notes 修改状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/11/18 11:54
     */
    public function status()
    {
        $params = (new CourseCatalogueValidate())->post()->goCheck('status');
        $result = (new CourseCatalogueLogic())->changeStatus($params);
        if(true === $result){
            return $this->success('修改成功');
        }
        return $this->fail($result);
    }

}