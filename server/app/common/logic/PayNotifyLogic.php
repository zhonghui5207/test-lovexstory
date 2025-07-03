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
use app\common\enum\AccountLogEnum;
use app\common\enum\marketing\CouponEnum;
use app\common\enum\notice\NoticeEnum;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\enum\PayEnum;
use app\common\model\marketing\CouponList;
use app\common\model\order\Order;
use app\common\model\questionbank\QuestionbankRecord;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\service\ConfigService;
use think\facade\Db;
use think\facade\Log;

/**
 * 支付成功后处理订单状态
 * Class PayNotifyLogic
 * @package app\api\logic
 */
class PayNotifyLogic extends BaseLogic
{
    public static function handle($action, $orderSn, $extra = [])
    {
        Db::startTrans();
        try {
            self::$action($orderSn, $extra);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            Log::write(implode('-', [
                __CLASS__,
                __FUNCTION__,
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ]));
            self::setError($e->getMessage());
            return $e->getMessage();
        }
    }


    //下单回调 //调用回调方法统一处理 更新订单支付状态
    private static function order($orderSn, $extra = [])
    {
        $order = Order::with(['order_course'])->where(['sn' => $orderSn])->findOrEmpty();

        //增加用户累计消费额度
        User::where(['id' => $order['user_id']])
            ->inc('total_order_amount', $order['order_amount'])
            ->inc('total_order_num')
            ->update();

        //更新订单状态
        Order::update([
            'pay_status' => PayEnum::ISPAID,
            'pay_time' => time(),
            'order_status' => OrderEnum::ORDER_STSTUS_COMPLETE,
            'transaction_id' => $extra['transaction_id'] ?? '',
            'cancel_time'   => '',//清掉取消时间
            'order_amount' => round($order['order_amount'] - $order['discount_amount'],2),//使用优惠券，重新计算订单金额
        ], ['id' => $order['id']]);

        //更新优惠券状态
        if (isset($order['coupon_list_id']) && !empty($order['coupon_list_id'])) {
            CouponList::update(['order_id'=>$order->id,'status'=>CouponEnum::USE_STATUS_OK,'use_time'=>time()],['id'=>$order['coupon_list_id']]);
        }

//        //订单日志
        (new OrderLogLogic())->record(OrderLogEnum::TYPE_USER,OrderLogEnum::USER_PAID_ORDER,$order['id'],$order['user_id']);

        $courseId = $order['order_course'][0]['course_id'];
        //课程订单
        if ($order['type'] == OrderEnum::ORDER_TYPE_COURSE) {
            //加入学习，并更新统计学习人数
            CourseLogic::courseStatistics($order['user_id'],$courseId,$order['id']);

            //添加分销订单
            DistributionOrderLogic::add($order['id']);
        }
        //题库订单
        if ($order['type'] == OrderEnum::ORDER_TYPE_QUESTIONBANK) {
            QuestionbankRecord::create([
                'questionbank_id' => $courseId,
                'user_id' => $order['user_id'],
            ]);
        }


        // 消息通知 - 通知买家
//        event('Notice', [
//            'scene_id' =>  NoticeEnum::ORDER_PAY_NOTICE,
//            'params' => [
//                'user_id' => $order['user_id'],
//                'order_id' => $order['id']
//            ]
//        ]);

        // 消息通知 - 通知卖家
        $mobile = ConfigService::get('website', 'contact_phone');
        if (!empty($mobile)) {
            event('Notice', [
                'scene_id' =>  NoticeEnum::ORDER_PAY_NOTICE_PLATFORM,
                'params' => [
                    'mobile' => $mobile,
                    'order_id' => $order['id']
                ]
            ]);
        }

    }

    /**
     * @notes 充值成功回调
     * @param $orderSn
     * @param array $extra
     * @author Tab
     * @date 2021/8/11 14:43
     */
    public static function recharge($orderSn, $extra = [])
    {
        $order = RechargeOrder::where('sn', $orderSn)->findOrEmpty();
        // 增加用户累计充值金额及用户余额
        $user = User::findOrEmpty($order->user_id);
        $user->total_recharge_amount = $user->total_recharge_amount + $order->order_amount;
        $user->user_money = $user->user_money + $order->order_amount;
        $user->save();

        // 记录账户流水
        AccountLogLogic::record($order->user_id, AccountLogEnum::BNW,AccountLogEnum::BNW_INC_RECHARGE, AccountLogEnum::INC, $order->order_amount, $order->sn, '用户充值');

        // 更新充值订单状态
        $order->transaction_id = $extra['transaction_id'];
        $order->pay_status = PayEnum::ISPAID;
        $order->pay_time = time();
        $order->save();

        // 充值奖励
        foreach($order->award as $item) {
            if(isset($item['give_money']) && $item['give_money'] > 0) {
                // 充值送余额
                self::awardMoney($order, $item['give_money']);
            }
        }

    }

    /**
     * @notes 充值送余额
     * @param $userId
     * @param $giveMoney
     * @author Tab
     * @date 2021/8/11 14:35
     */
    public static function awardMoney($order, $giveMoney)
    {
        // 充值送余额
        $user = User::findOrEmpty($order->user_id);
        $user->user_money = $user->user_money + $giveMoney;
        $user->save();
        // 记录账户流水
        AccountLogLogic::record($order->user_id, AccountLogEnum::BNW,AccountLogEnum::BNW_INC_RECHARGE_GIVE, AccountLogEnum::INC, $giveMoney, $order->sn, '充值赠送');
    }


    public static function getOrderInfo($orderId)
    {
        $field = [
            'id',
            'sn',
            'pay_way',
            'delivery_type',
            'goods_price',
            'order_amount',
            'discount_amount',
            'express_price',
            'user_remark',
            'address',
            'selffetch_shop_id',
            'create_time',
        ];
        $order = Order::field($field)->with(['orderGoods' => function($query) {
            $query->field(['goods_num', 'order_id', 'goods_price', 'goods_snap']);
        }])
            ->append(['delivery_address', 'pay_way_desc', 'delivery_type_desc'])
            ->findOrEmpty($orderId);
        if ($order->isEmpty()) {
            throw new \Exception("订单不存在");
        }

        return $order->toArray();
    }

}