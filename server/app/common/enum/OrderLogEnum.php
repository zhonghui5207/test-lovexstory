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
 * 订单日志枚举类
 * Class OrderLogEnum
 * @package app\common\enum
 */
class OrderLogEnum
{
    //操作人类型
    const TYPE_USER         = 1;    //会员
    const TYPE_ADMIN        = 2;    //平台
    const TYPE_SYSTEM       = 3;    //系统

    //订单动作
    const USER_ADD_ORDER            = 101;//提交订单
    const USER_PAID_ORDER           = 102;//支付订单
    const USER_CANCEL_ORDER         = 103;//取消订单

    const PLATFORM_ORDER_REMARKS    = 201;//平台备注
    const PLATFORM_CANCEL_ORDER     = 202;//平台取消订单
    const PLATFORM_REFUND_ORDER     = 203;//平台退款

    const SYSTEM_CANCEL_REMARKS     = 301;//系统取消订单


    /**
     * @notes 订单日志明细
     * @param bool $value
     * @return string|string[]
     * @author 段誉
     * @date 2021/8/2 14:32
     */
    public static function getRecordDesc($value = true)
    {
        $desc = [
            //会员
            self::USER_ADD_ORDER                => '会员提交订单',
            self::USER_CANCEL_ORDER             => '会员取消订单',
            self::USER_PAID_ORDER               => '会员支付订单',
            //平台
            self::PLATFORM_ORDER_REMARKS        => '平台备注订单',
            self::PLATFORM_CANCEL_ORDER         => '平台取消订单',
            self::PLATFORM_REFUND_ORDER         => '平台退款',
            //
            self::SYSTEM_CANCEL_REMARKS         => '系统取消订单',

        ];

        if (true === $value) {
            return $desc;
        }
        return $desc[$value];
    }
}