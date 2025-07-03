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

namespace app\adminapi\controller\finance;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\finance\FinanceLogic;

/**
 * 财务控制器
 * Class FinanceController
 * @package app\adminapi\controller\finance
 */
class FinanceController extends BaseAdminController
{
    /**
     * @notes 数据中心
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/25 19:38
     */
    public function dataCenter()
    {
        $result = FinanceLogic::dataCenter();
        return $this->data($result);
    }
}