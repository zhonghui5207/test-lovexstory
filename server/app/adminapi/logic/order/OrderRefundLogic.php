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

namespace app\adminapi\logic\order;

use app\common\enum\OrderRefundEnum;
use app\common\logic\BaseLogic;
use app\common\model\order\OrderRefund;
use app\common\model\order\OrderRefundLog;
use think\facade\Db;

class OrderRefundLogic extends BaseLogic
{

    public function otherLists()
    {
        $lists = OrderRefundEnum::getTypeDesc();
        return $lists;
    }
    /**
     * @notes 重新退款
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2022/9/9 6:18 下午
     */
    public function reRefund($params)
    {
        // 启动事务
        Db::startTrans();
        try {
            //新增退款日志
            OrderRefundLog::create([
                'sn' => generate_sn(new OrderRefundLog(), 'sn'),
                'refund_id' => $params['id'],
                'type' => OrderRefundEnum::TYPE_ADMIN,
                'operator_id' => $params['admin_id'],
            ]);

            //更新退款记录状态
            OrderRefund::update(['refund_status'=>OrderRefundEnum::STATUS_ING],['id'=>$params['id']]);

            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $e->getMessage();
        }
    }
}