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
namespace app\adminapi\controller\teacher;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\teacher\TeacherLogic;
use app\adminapi\validate\teacher\TeacherValidate;

/**
 * 讲师控制器类
 * Class TeacherController
 * @package app\adminapi\controller\teacher
 */
class TeacherController extends BaseAdminController
{

    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 添加讲师
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 11:28
     */
    public function add()
    {
        $params = (new TeacherValidate())->post()->goCheck('add');
        (new TeacherLogic())->add($params);
        return $this->success('添加成功');
    }

    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/20 11:29
     */
    public function detail()
    {
        $params = (new TeacherValidate())->goCheck('detail');
        $detail = (new TeacherLogic())->detail($params['id']);
        return $this->success('',$detail);

    }

    /**
     * @notes 编辑讲师
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 11:30
     */
    public function edit()
    {
        $params = (new TeacherValidate())->post()->goCheck('edit');
        (new TeacherLogic())->edit($params);
        return $this->success('修改成功');

    }

    /**
     * @notes 删除讲师
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 11:31
     */
    public function del()
    {
        $params = (new TeacherValidate())->post()->goCheck('id');
        (new TeacherLogic())->del($params['id']);
        return $this->success('删除成功');
    }

    /**
     * @notes 修改状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 11:32
     */
    public function status()
    {
        $params = (new TeacherValidate())->post()->goCheck('status');
        (new TeacherLogic())->status($params);
        return $this->success('修改成功');

    }

}