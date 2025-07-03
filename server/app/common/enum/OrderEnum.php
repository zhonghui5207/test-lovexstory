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
namespace app\common\enum;
/**
 * 订单枚举类
 * Class OrderEnum
 * @package app\common\enum
 */
class OrderEnum
{
    const ORDER_STSTUS_WAITPAY      = 1;    //待支付
//    const ORDER_STSTUS_WAITDELIVER  = 2;    //待发货
//    const ORDER_STSTUS_WAITTAKE     = 3;    //待收货
    const ORDER_STSTUS_COMPLETE     = 2;    //已完成
    const ORDER_STSTUS_CLONE        = 3;    //已关闭


    const PAY_STATUS_WAIT           = 0;
    const PAY_STATUS_PAY            = 1;

    const REFUND_STATUS_NOT         = 1;    //未退款
    const REFUND_STATUS_PART        = 2;    //部分退款
    const REFUND_STATUS_ALL         = 3;    //全部退款

    //订单类型：1-课程订单；2-题库订单；
    const ORDER_TYPE_COURSE         = 1;    //课程订单
    const ORDER_TYPE_QUESTIONBANK   = 2;    //题库订单

    /**
     * @notes 获取支付类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/16 16:38
     */
    public static function getPayStatusDesc($from = true)
    {
        $desc = [
            self::PAY_STATUS_WAIT   => '待支付',
            self::PAY_STATUS_PAY    => '已支付',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取订单状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/9 10:51
     */
    public static function getOrderStatusDesc($from = true)
    {
        $desc = [
            self::ORDER_STSTUS_WAITPAY          => '待付款',
//            self::ORDER_STSTUS_WAITDELIVER      => '待发货',
//            self::ORDER_STSTUS_WAITTAKE         => '待收货',
            self::ORDER_STSTUS_COMPLETE         => '已完成',
            self::ORDER_STSTUS_CLONE            => '已关闭',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 支付状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/15 16:22
     */
    public static function getPatStatusDesc($from = true)
    {
        $desc = [
            self::PAY_STATUS_WAIT   => '未支付',
            self::PAY_STATUS_PAY    => '已支付',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取退款状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/15 16:09
     */
    public static function getRefundStatusDesc($from = true)
    {
        $desc = [
            self::REFUND_STATUS_NOT     => '未退款',
//            self::REFUND_STATUS_PART    => '部分退款',
            self::REFUND_STATUS_ALL     => '全部退款',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }


}