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

namespace app\common\command;
use app\common\enum\OrderRefundEnum;
use app\common\enum\PayEnum;
use app\common\logic\RefundLogic;
use app\common\model\order\Order;
use app\common\model\order\OrderRefundLog;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\facade\Log;

class OrderRefund extends Command
{
    protected function configure()
    {
        $this->setName('order_refund')
            ->setDescription('订单退款');
    }

    protected function execute(Input $input, Output $output)
    {
        $lists = OrderRefundLog::alias('orl')
            ->join('order_refund or', 'or.id = orl.refund_id')
            ->field('or.order_terminal,or.transaction_id,orl.sn,or.order_amount,or.refund_amount,orl.refund_id,orl.id as refund_log_id,or.user_id,or.order_id')
            ->where(['orl.refund_status'=>OrderRefundEnum::STATUS_ING])
            ->select()
            ->toArray();

        if (empty($lists)) {
            return true;
        }

        $orderIds = array_column($lists,'order_id');
        $orderLists = Order::where(['id'=>$orderIds])->column('*','id');
        Db::startTrans();
        try{
            foreach ($lists as $val) {
                $order = $orderLists[$val['order_id']] ?? [];
                $order['refund_id'] = $val['refund_id'];
                $order['refund_log_id'] = $val['refund_log_id'];

                if(empty($order)){
                    continue;
                }

                switch ($order['pay_way']) {
                    //余额退款
                    case PayEnum::BALANCE_PAY:
                        $result = RefundLogic::balancePayRefund($order);
                        break;
                    //微信退款
                    case PayEnum::WECHAT_PAY:
                        $result = RefundLogic::wechatPayRefund($order);
                        break;
                }
                if (true === $result) {

                }
            }

            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            Log::write('订单退款失败,失败原因:' . $e->getMessage());
            return false;
        }
    }
}