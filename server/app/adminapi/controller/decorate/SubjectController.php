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
namespace app\adminapi\controller\decorate;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\decorate\SubjectLogic;
use app\adminapi\validate\decorate\SubjectValidate;

/**
 * 专区专题控制器类
 * Class SubjectController
 * @package app\adminapi\controller\decorate
 */
class SubjectController extends BaseAdminController
{

    /**
     * @notes 其他列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 18:59
     */
    public function otherLists()
    {
        $lists = (new SubjectLogic())->otherLists();
        return $this->success('',$lists);
    }

    /**
     * @notes 专题专区列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 17:28
     */
    public function lists()
    {
        return $this->dataLists();
    }


    /**
     * @notes 添加专区
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 18:14
     */
    public function add()
    {
        $params = (new SubjectValidate())->post()->goCheck('add');
        (new SubjectLogic())->add($params);
        return $this->success('添加成功');
    }

    /**
     * @notes 修改专区
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 18:25
     */
    public function edit()
    {
        $params = (new SubjectValidate())->post()->goCheck('edit');
        (new SubjectLogic())->edit($params);
        return $this->success('修改成功');
    }

    /**
     * @notes 获取专区详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/1 15:49
     */
    public function detail()
    {
        $params = (new SubjectValidate())->get()->goCheck('detail');
        $result = (new SubjectLogic())->detail($params['id']);
        return $this->success('',$result);
    }

    /**
     * @notes 删除专区
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 18:40
     */
    public function del()
    {
        $params = (new SubjectValidate())->post()->goCheck('del');
        (new SubjectLogic())->del($params['id']);
        return $this->success('删除成功',[],1,1);
    }


    /**
     * @notes 修改专区状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/7/7 15:51
     */
    public function status()
    {
        $params = (new SubjectValidate())->post()->goCheck('status');
        (new SubjectLogic())->status($params);
        return $this->success('修改成功');
    }

}