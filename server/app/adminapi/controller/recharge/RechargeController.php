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

namespace app\adminapi\controller\recharge;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\recharge\RechargeLogic;

/**
 * 充值控制器
 * Class RechargeController
 * @package app\adminapi\controller\recharge
 */
class RechargeController extends BaseAdminController
{
    /**
     * @notes 获取充值设置
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2022/5/24 6:05 下午
     */
    public function getRechargeConfig()
    {
        $result = RechargeLogic::getRechargeConfig();
        return $this->data($result);
    }

    /**
     * @notes 充值设置
     * @return \think\response\Json
     * @author ljj
     * @date 2022/5/24 6:05 下午
     */
    public function setRechargeConfig()
    {
        $params = $this->request->post();
        $result = RechargeLogic::setRechargeConfig($params);
        if($result) {
            return $this->success('保存成功',[],1,1);
        }
        return $this->fail(RechargeLogic::getError());
    }

    /**
     * @notes 充值记录列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/11 15:22
     */
    public function lists()
    {
        return $this->dataLists();
    }
}