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
namespace app\api\controller;
use app\api\logic\TeacherLogic;

/**
 * 讲师控制器类
 * Class TeacherController
 * @package app\api\controller
 */
class TeacherController extends BaseApiController
{

    /**
     * @notes 获取讲师详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/8 18:59
     */
    public function detail()
    {
        $id = $this->request->get('id');
        $detail = (new TeacherLogic())->detail($id);
        return $this->success('',$detail);
    }

}