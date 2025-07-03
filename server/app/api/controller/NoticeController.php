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

use app\api\logic\NoticeLogic;

/**
 * 消息控制器
 * Class NoticeController
 * @package app\api\controller
 */
class NoticeController extends BaseApiController
{
    /**
     * @notes 消息中心
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/25 9:58
     */
    public function index()
    {
        $result = NoticeLogic::index($this->userId);
        return $this->data($result);
    }

    /**
     * @notes 通知列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/25 10:33
     */
    public function lists()
    {
        return $this->dataLists();
    }
}