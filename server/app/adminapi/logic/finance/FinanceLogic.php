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

namespace app\adminapi\logic\finance;

use app\common\enum\DistributionEnum;
use app\common\enum\OrderEnum;
use app\common\logic\BaseLogic;
use app\common\model\distribution\DistributionOrder;
use app\common\model\IndexVisit;
use app\common\model\order\Order;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;

/**
 * 财务逻辑层
 * Class FinanceLogic
 * @package app\adminapi\logic\finance
 */
class FinanceLogic extends BaseLogic
{
    /**
     * @notes 数据中心
     * @return array
     * @author Tab
     * @date 2021/8/25 19:55
     */
    public static function dataCenter()
    {
        // 订单总支付金额
        $orderSum = Order::where(['pay_status'=>OrderEnum::PAY_STATUS_PAY])->sum('order_amount');
        // 订单总支付笔数
        $orderNum = Order::where(['pay_status'=>OrderEnum::PAY_STATUS_PAY])->count('id');
        // 退款成功总金额
        $refundSum = Order::where(['order_status'=>OrderEnum::ORDER_STSTUS_CLONE])
                    ->where('refund_status','>',OrderEnum::REFUND_STATUS_NOT)
                    ->sum('total_amount');
        // 退款成功笔数
        $refundNum = Order::where(['order_status'=>OrderEnum::ORDER_STSTUS_CLONE])
                    ->where('refund_status','>',OrderEnum::REFUND_STATUS_NOT)
                    ->sum('id');
        // 用户总余额
        $userMoneySum = User::sum('user_money');
        // 用户人数
        $userNum = User::count();
        //总访问量
        $visitor = IndexVisit::distinct(true)->count('ip');
        //累计充值金额
        $rechargeSum = RechargeOrder::where(['pay_status'=>1])->sum('order_amount');
        //累计充值次数
        $rechargeNum = RechargeOrder::where(['pay_status'=>1])->count();
        //累计充值奖励
        $awardLists  = RechargeOrder::where(['pay_status'=>1])
                ->where('award','>',0)
                ->column('award');
        $totalAward = 0;
        foreach ($awardLists as $award){
            $award = json_decode($award,true);
            $totalAward += $award[0]['give_money'];
        }

        //今日入账佣金
        $today_earnings = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_SUCCESS])->whereDay('settlement_time')->sum('earnings');
        //今日新增待结算佣金
        $today_wait_earnings = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_WAIT])->whereDay('create_time')->sum('earnings');
        //待结算佣金
        $wait_earnings = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_WAIT])->sum('earnings');
        //累计已入账佣金
        $total_earnings = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_SUCCESS])->sum('earnings');

        $data = [
            'order_sum'         => $orderSum,
            'order_num'         => $orderNum,
            'refund_sum'        => $refundSum,
            'refund_num'        => $refundNum,
            'user_money_sum'    => $userMoneySum,
            'user_num'          => $userNum,
            'visitor'           => $visitor,
            'recharge_sum'      => $rechargeSum,
            'recharge_num'      => $rechargeNum,
            'award_sum'         => round($totalAward,2),
            'today_earnings'      => $today_earnings,
            'today_wait_earnings'      => $today_wait_earnings,
            'wait_earnings'      => $wait_earnings,
            'total_earnings'      => $total_earnings,
        ];
        return $data;
    }
}