<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
use app\common\command\CouponEnd;
use app\common\command\CouponListEnd;
use app\common\command\Crontab;
use app\common\command\DistributionSettlement;
use app\common\command\OrderClose;
use app\common\command\OrderRefund;
use app\common\command\UpdateDistributionLevel;
use app\common\command\UploadShippingInfo;
use app\common\command\WechatMerchantTransfer;

return [
    // 指令定义
    'commands' => [
        // 定时任务
        'crontab' => Crontab::class,
        // 订单自动关闭超时未付款订单
        'order_close' => OrderClose::class,
        //订单退款
        'order_refund' => OrderRefund::class,
        //用户优惠券失效
        'coupon_list_end' => CouponListEnd::class,
        //优惠券活动结束
        'coupon_end' => CouponEnd::class,
        //结算分销订单
        'distribution_settlement' => DistributionSettlement::class,
        //更新分销等级
        'update_distribution_level' => UpdateDistributionLevel::class,
        //商家转账到零钱查询
        'wechat_merchant_transfer' => WechatMerchantTransfer::class,
        //上传发货信息
        'upload_shipping_info' => UploadShippingInfo::class,
    ],
];
