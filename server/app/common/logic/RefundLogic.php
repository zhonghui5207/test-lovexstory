<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------


namespace app\common\logic;

use app\common\enum\PayEnum;
use app\common\model\order\OrderRefund;
use app\common\model\order\OrderRefundLog;
use app\common\service\pay\BalancePayService;
use app\common\service\pay\WeChatPayService;
use app\common\service\wechat\WeChatConfigService;

/**
 * 订单退款逻辑
 * Class OrderRefundLogic
 * @package app\common\logic
 */
class RefundLogic extends BaseLogic
{

    protected static $refund;

    /**
     * @notes 发起退款
     * @param $order //订单信息
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author 段誉
     * @date 2021/8/9 17:31
     */
    public static function refund($order,$type,$operator_id)
    {
        if ($order['order_amount'] <= 0) {
            return false;
        }

        self::log($order,$type,$operator_id);

        //通过计划任务来退款
//        switch ($order['pay_way']) {
//            //余额退款
//            case PayEnum::BALANCE_PAY:
//                self::balancePayRefund($order);
//                break;
//            //微信退款
//            case PayEnum::WECHAT_PAY:
//                self::wechatPayRefund($order);
//                break;
//        }

        return true;
    }



    /**
     * @notes 余额退款
     * @param $order
     * @param $refundAmount
     * @author 段誉
     * @date 2021/8/5 10:25
     */
    public static function balancePayRefund($order)
    {
        return (new BalancePayService())->refund($order);
    }



    /**
     * @notes 微信退款
     * @param $refundWay
     * @param $order
     * @param $refundAmount
     * @return bool|void
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author 段誉
     * @date 2021/8/5 10:25
     */
    public static function wechatPayRefund($order)
    {
        //原来退回到微信的情况
        $wechatConfig = WeChatConfigService::getWechatConfigByTerminal($order['order_terminal']);

        if (!isset($wechatConfig['cert_path']) || !isset($wechatConfig['key_path'])) {
            throw new \Exception('请联系管理员设置微信证书!');
        }

        if (!file_exists($wechatConfig['cert_path']) || !file_exists($wechatConfig['key_path'])) {
            throw new \Exception('微信证书不存在,请联系管理员!');
        }

        //发起退款
        $result = (new WeChatPayService($order['order_terminal']))->refund([
            'transaction_id' => $order['transaction_id'],
            'refund_sn' => $order['sn'],
            'total_fee' => $order['order_amount'] * 100,//订单金额,单位为分
            'refund_fee' => intval($order['order_amount'] * 100),//退款金额
        ]);
        //更新退款日志记录
        OrderRefundLog::update([
            'wechat_refund_id' => $result['refund_id'] ?? 0,
            'refund_status' => (isset($result['result_code']) && $result['result_code'] == 'SUCCESS') ? 1 : 2,
            'refund_msg' => json_encode($result, JSON_UNESCAPED_UNICODE),
        ], ['id' => $order['refund_log_id']]);

        //更新订单退款状态
        OrderRefund::update([
            'refund_status' => (isset($result['result_code']) && $result['result_code'] == 'SUCCESS') ? 1 : 2,
        ], ['id'=>$order['refund_id']]);

        if ($result['return_code'] == 'FAIL' || $result['result_code'] == 'FAIL') {
            return false;
        }

        return true;
    }



    /**
     * @notes 退款日志
     * @param $order
     * @param $refundAmount
     * @author 段誉
     * @date 2021/8/9 17:32
     */
    public static function log($order,$type,$operator_id)
    {
        $result = OrderRefund::create([
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'sn' => generate_sn(new OrderRefund(), 'sn'),
            'order_amount' => $order['order_amount'],
            'type'          => $type,
            'refund_amount' => $order['order_amount'],
        ]);
        //退款日志
        OrderRefundLog::create([
            'sn' => generate_sn(new OrderRefundLog(), 'sn'),
            'refund_id' => $result->id,
            'type' => $type,
            'operator_id' => $operator_id,
        ]);

        self::$refund = $result;
    }

}