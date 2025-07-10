<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\logic;

use app\common\enum\marketing\CouponEnum;
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\enum\YesNoEnum;
use app\common\model\course\StudyCourse;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;
use app\common\model\order\Order;
use app\common\model\order\OrderCourse;
use app\common\model\pay\PayWay;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\pay\AliPayService;
use app\common\service\pay\BalancePayService;
use app\common\service\pay\WeChatPayService;


/**
 * 支付逻辑
 * Class PaymentLogic
 * @package app\common\logic
 */
class PaymentLogic extends BaseLogic
{
    /**
     * @notes 支付
     * @param $payWay // 支付方式
     * @param $from //订单来源(商品订单?充值订单?其他订单?)
     * @param $order //订单信息
     * @param $terminal //终端
     * @return array|bool|string|void
     * @throws \Exception
     * @author 段誉
     * @date 2021/7/29 14:49
     */
    public static function pay($payWay, $from, $order, $terminal,$params)
    {
        //更新支付方式
        switch ($from) {
            case 'order':
                //校验优惠券
                if (isset($params['coupon_list_id']) && $params['coupon_list_id'] != '') {
                    if ($order['order_amount'] <= 0) {
                        self::$error = '订单不满足优惠券使用要求';
                        return false;
                    }
                    $coupon_list = CouponList::where(['id'=>$params['coupon_list_id']])->findOrEmpty()->toArray();
                    if (empty($coupon_list) || $coupon_list['status'] == CouponEnum::USE_STATUS_EXPIRE || $coupon_list['status'] == CouponEnum::USE_STATUS_VOID) {
                        self::$error = '优惠券不存在或已过期';
                        return false;
                    }
                    if ($coupon_list['status'] == CouponEnum::USE_STATUS_OK) {
                        self::$error = '优惠券已被使用';
                        return false;
                    }
                    $coupon = Coupon::where(['id'=>$coupon_list['coupon_id']])
                        ->with(['course_list'])
                        ->findOrEmpty()
                        ->toArray();
                    if ($coupon['use_scope'] == CouponEnum::USER_SCOPE_COUSE_USABLE) {
                        $course_ids = array_column($coupon['course_list'],'course_id');
                        if (!in_array($order['order_course'][0]['course_id'], $course_ids)) {
                            self::$error = '订单课程不适用';
                            return false;
                        }
                    }
                    if ($coupon['use_scope'] == CouponEnum::USER_SCOPE_COUSE_NUSABLE) {
                        $course_ids = array_column($coupon['course_list'],'course_id');
                        if (in_array($order['order_course'][0]['course_id'], $course_ids)) {
                            self::$error = '指定课程不可用';
                            return false;
                        }
                    }
                    if ($coupon['use_condition'] == CouponEnum::USE_CONDITION_MONEY) {
                        if ($order['order_amount'] < $coupon['condition_money']) {
                            self::$error = '订单金额不满足优惠券使用要求';
                            return false;
                        }
                    }
                    if ($coupon['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED && (strtotime($coupon['use_time_start']) > time() || strtotime($coupon['use_time_end']) < time())) {
                        self::$error = '不符合优惠券使用时间';
                        return false;
                    }
                    if ($coupon['use_time_type'] == CouponEnum::USE_TYPE_TYPE_TODAY && $coupon_list['invalid_time'] < time()) {
                        self::$error = '不符合优惠券使用时间';
                        return false;
                    }
                    if ($coupon['use_time_type'] == CouponEnum::USE_TYPE_TYPE_MORROW) {
                        $start_time = strtotime(date("Y-m-d", strtotime("+1 day", strtotime($coupon_list['create_time']))).' 00:00:00');
                        if ($start_time > time() || $coupon_list['invalid_time'] < time()) {
                            self::$error = '不符合优惠券使用时间';
                            return false;
                        }
                    }
                }
                //优惠券金额
                $discount_amount = $coupon['money'] ?? 0;
                if ($discount_amount > $order['order_amount']) {
                    $discount_amount = $order['order_amount'];
                }

                //更新订单信息
                Order::update(['pay_way' => $payWay,'coupon_list_id' => $params['coupon_list_id'] ?? 0,'discount_amount' => ['inc',$discount_amount]], ['id' => $order['id']]);
                $order['order_amount'] = $order['order_amount'] - $discount_amount;
                break;
            case 'recharge':
                RechargeOrder::update(['pay_way' => $payWay], ['id' => $order['id']]);
                break;
        }

        if($order['order_amount'] == 0) {
            PayNotifyLogic::handle($from, $order['sn']);
            return ['pay_way'=>PayEnum::BALANCE_PAY];
        }
        switch ($payWay) {
            case PayEnum::BALANCE_PAY:
                //余额支付
                $payService = (new BalancePayService());
                $result = $payService->pay($from, $order);
                if (false !== $result) {
                    PayNotifyLogic::handle($from, $order['sn']);
                }
                break;
            case PayEnum::WECHAT_PAY:
                $payService = (new WeChatPayService($terminal, $order['user_id'] ?? null));
                $result = $payService->pay($from, $order);
                break;
            case PayEnum::ALI_PAY:
                //支付宝支付
                $payService = (new AliPayService($terminal));
                $result = $payService->pay($from, $order);
                break;
            default:
                self::$error = '订单异常';
                $result = false;
        }
        //支付成功, 执行支付回调
        if (false === $result && !self::hasError()) {
            self::setError($payService->getError());
        }
        return $result;
    }


    /**
     * @notes 获取准备预支付订单信息
     * @param $params
     * @return Order|array|false|\think\Model
     * @author 段誉
     * @date 2021/8/3 11:57
     */
    public static function getPayOrderInfo($params)
    {
        try {
            switch ($params['from']) {
                case 'order':
                    $order = Order::with('order_course')->findOrEmpty($params['order_id']);
                    if ($order->isEmpty()
                        || $order['order_status'] == OrderEnum::ORDER_STSTUS_CLONE
                        || $order['delete_time'] > 0)
                    {
                        throw new \Exception('订单已关闭');
                    }

                    //查看我的学习也没有该课程，防止二次购买
                    if ($order['type'] == OrderEnum::ORDER_TYPE_COURSE) {
                        $orderCourse = StudyCourse::alias('SO')
                            ->where(['user_id' => $order->user_id,'course_id'=>$order->order_course[0]['course_id']])
                            ->field('id')
                            ->findOrEmpty();
                        if(!$orderCourse->isEmpty()){
                            throw new \Exception('该课程已经购买，请勿重复购买');
                        }
                    }
                    if ($order['type'] == OrderEnum::ORDER_TYPE_QUESTIONBANK) {
                        $orderCourse = Order::alias('o')
                            ->join('order_course oc','oc.order_id = o.id')
                            ->where(['oc.course_id'=>$order->order_course[0]['course_id'],'o.user_id'=>$order->user_id,'o.order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'o.refund_status'=>OrderEnum::REFUND_STATUS_NOT])
                            ->findOrEmpty();
                        if (!$orderCourse->isEmpty()) {
                            return '该题库已经购买，请勿重复购买';
                        }
                    }

                    break;
                case 'recharge':
                    $order = RechargeOrder::findOrEmpty($params['order_id']);
                    if($order->isEmpty()) {
                        throw new \Exception('充值订单不存在');
                    }
                    break;
            }

            if ($order['pay_status'] == PayEnum::ISPAID) {
                throw new \Exception('订单已支付');
            }
            return $order;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    //获取支付方式列表
    public static function getPayWay($userId, $terminal, $params)
    {
        try {
            // 获取待支付金额
            if ($params['from'] == 'order') {
                // 订单
                $order = Order::findOrEmpty($params['order_id'])->toArray();
            }
            if ($params['from'] == 'recharge') {
                // 充值
                $order = RechargeOrder::findOrEmpty($params['order_id'])->toArray();
            }
            if (empty($order) || !isset($order)) {
                throw new \Exception('待支付订单不存在');
            }

            // 获取订单剩余支付时间
            $cancelUnpaidOrders = ConfigService::get('transaction', 'cancel_unpaid_orders');
            $cancelUnpaidOrdersTimes = ConfigService::get('transaction', 'cancel_unpaid_orders_times');

            if (empty($cancelUnpaidOrders)) {
                // 不自动取消待支付订单
                $cancelTime = 0;
            } else {
                // 指定时间内取消待支付订单
                $cancelTime = strtotime($order['create_time']) + intval($cancelUnpaidOrdersTimes) * 60;
            }

            $payWay = PayWay::alias('pw')
                ->join('dev_pay dp', 'pw.pay_id = dp.id')
                ->where(['pw.scene'=>$params['scene'],'pw.status'=>YesNoEnum::YES])
                ->field('dp.id,dp.name,dp.pay_way,dp.image')
                ->order(['sort'=>'asc','id'=>'desc'])
                ->select()
                ->toArray();
            foreach ($payWay as $k=>&$item) {

                if ($item['pay_way'] == PayEnum::WECHAT_PAY) {
                    $item['extra'] = '微信快捷支付';
                }

                if ($item['pay_way'] == PayEnum::ALI_PAY) {
                    $item['extra'] = '支付宝快捷支付';
                }

                if ($item['pay_way'] == PayEnum::BALANCE_PAY) {
                    $user_money = User::where(['id' => $userId])->value('user_money');
                    $item['extra'] = '可用余额:'.$user_money;
                }
                // 充值时去除余额支付
                if ($params['from'] == 'recharge' && $item['pay_way'] == PayEnum::BALANCE_PAY) {
                    unset($payWay[$k]);
                }
            }

            return [
                'lists' => array_values($payWay),
                'order_amount' => $order['order_amount'],
                'cancel_time' => $cancelTime,
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 查看订单支付状态
     * @param $params
     * @return array|false
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function getPayStatus($params)
    {
        try {
            if ($params['from'] == 'order') {
                $order = Order::with('orderGoods')->findOrEmpty($params['order_id']);
                $payStatus = $order->pay_status;
                $orderInfo = '';
            } else if ($params['from'] == 'recharge') {
                $order = RechargeOrder::findOrEmpty($params['order_id']);
                $orderInfo = ''; // 充值无需返回订单详情
                $payStatus =  $order->getData('pay_status');
            }
            if ($order->isEmpty()) {
                throw new \Exception('订单不存在');
            }
            return [
                'pay_status' => $payStatus,
                'pay_way' => $order->pay_way,
                'order' => $orderInfo
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 获取支付结果
     * @param $params
     * @return array
     * @author 段誉
     * @date 2022/4/6 15:23
     */
    public static function getPayResult($params)
    {
        switch ($params['from']) {
            case 'order' :
                $result = Order::where(['id' => $params['order_id']])
                    ->field(['id', 'sn', 'pay_time', 'pay_way', 'order_amount', 'pay_status'])
                    ->findOrEmpty()
                    ->toArray();
                $result['total_amount'] = '￥' . $result['order_amount'];
                break;
            default :
                $result = [];
        }

        if (empty($result)) {
            self::$error = '订单信息不存在';
        }

        $result['pay_way'] = PayEnum::getPayTypeDesc($result['pay_way']);
        return $result;
    }


    /**
     * @notes 获取订单金额
     * @param $params
     * @return array|false|int[]
     * @author ljj
     * @date 2023/8/24 6:20 下午
     */
    public static function orderAmount($params)
    {
        switch ($params['from']) {
            case 'order':
                $order = Order::with('order_course')->findOrEmpty($params['order_id'])->toArray();
                //校验优惠券
                if (isset($params['coupon_list_id']) && $params['coupon_list_id'] != '') {
                    if ($order['order_amount'] <= 0) {
                        self::$error = '订单不满足优惠券使用要求';
                        return false;
                    }
                    $coupon_list = CouponList::where(['id'=>$params['coupon_list_id']])->findOrEmpty()->toArray();
                    if (empty($coupon_list) || $coupon_list['status'] == CouponEnum::USE_STATUS_EXPIRE || $coupon_list['status'] == CouponEnum::USE_STATUS_VOID) {
                        self::$error = '优惠券不存在或已过期';
                        return false;
                    }
                    if ($coupon_list['status'] == CouponEnum::USE_STATUS_OK) {
                        self::$error = '优惠券已被使用';
                        return false;
                    }
                    $coupon = Coupon::where(['id'=>$coupon_list['coupon_id']])
                        ->with(['course_list'])
                        ->findOrEmpty()
                        ->toArray();
                    if ($coupon['use_scope'] == CouponEnum::USER_SCOPE_COUSE_USABLE) {
                        $course_ids = array_column($coupon['course_list'],'course_id');
                        if (!in_array($order['order_course'][0]['course_id'], $course_ids)) {
                            self::$error = '订单课程不适用';
                            return false;
                        }
                    }
                    if ($coupon['use_scope'] == CouponEnum::USER_SCOPE_COUSE_NUSABLE) {
                        $course_ids = array_column($coupon['course_list'],'course_id');
                        if (in_array($order['order_course'][0]['course_id'], $course_ids)) {
                            self::$error = '指定课程不可用';
                            return false;
                        }
                    }
                    if ($coupon['use_condition'] == CouponEnum::USE_CONDITION_MONEY) {
                        if ($order['order_amount'] < $coupon['condition_money']) {
                            self::$error = '订单金额不满足优惠券使用要求';
                            return false;
                        }
                    }
                    if ($coupon['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED && (strtotime($coupon['use_time_start']) > time() || strtotime($coupon['use_time_end']) < time())) {
                        self::$error = '不符合优惠券使用时间';
                        return false;
                    }
                    if ($coupon['use_time_type'] == CouponEnum::USE_TYPE_TYPE_TODAY && $coupon_list['invalid_time'] < time()) {
                        self::$error = '不符合优惠券使用时间';
                        return false;
                    }
                    if ($coupon['use_time_type'] == CouponEnum::USE_TYPE_TYPE_MORROW) {
                        $start_time = strtotime(date("Y-m-d", strtotime("+1 day", strtotime($coupon_list['create_time']))).' 00:00:00');
                        if ($start_time > time() || $coupon_list['invalid_time'] < time()) {
                            self::$error = '不符合优惠券使用时间';
                            return false;
                        }
                    }
                }
                //优惠券金额
                $discount_amount = $coupon['money'] ?? 0;
                if ($discount_amount > $order['order_amount']) {
                    $discount_amount = $order['order_amount'];
                }
                $order['order_amount'] = $order['order_amount'] - $discount_amount;
                break;
            case 'recharge':
                $order = RechargeOrder::findOrEmpty($params['order_id'])->toArray();
                break;
        }

        return ['order_amount'=>$order['order_amount']];
    }

}