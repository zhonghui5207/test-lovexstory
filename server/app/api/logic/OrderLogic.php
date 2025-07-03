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
namespace app\api\logic;
use app\common\enum\CourseEnum;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\logic\BaseLogic;
use app\common\logic\OrderLogLogic;
use app\common\model\course\Course;
use app\common\model\order\Order;
use app\common\service\ConfigService;
use think\Exception;
use think\facade\Db;

/**
 * 订单逻辑类
 * Class OrderLogic
 * @package app\api\logic
 */
class OrderLogic extends BaseLogic
{

    /**
     * @notes 下单接口
     * @param array $params
     * @param array $userInfo
     * @author cjhao
     * @date 2022/6/9 11:01
     */
    public function sumbitOrder(array $params,array $userInfo)
    {
        try{
            Db::startTrans();

            $course = Course::where(['id'=>$params['course_id']])->findOrEmpty();
            if($course->isEmpty() || 0 == $course->status){
                throw new Exception('课程已下架');
            }
            if(CourseEnum::FEE_TYPE_FREE == $course->fee_type){
                throw new Exception('课程为免费课程，不需要购买');
            }
            $cancelOrder = ConfigService::get('transaction', 'cancel_unpaid_orders',1);
            $cancelOrderTimes = ConfigService::get('transaction', 'cancel_unpaid_orders_times',30) * 60;
            $cancelTime = 0;
            $now = time();
            if($cancelOrder){
                $cancelTime = $cancelOrderTimes + $now;
            }
            //写入
            $order                  = new Order();
            $order->user_id         = $userInfo['user_id'];
            $order->sn              = generate_sn($order,'sn');
            $order->order_terminal = $userInfo['terminal'];
            $order->order_status    = OrderEnum::ORDER_STSTUS_WAITPAY;
            $order->pay_status      = OrderEnum::PAY_STATUS_WAIT;
            $order->total_amount    = $course->sell_price;
            $order->order_amount    = $course->sell_price;
            $order->cancel_time     = $cancelTime;
            $order->save();

            $orderCourse[] = [
                'course_id'         => $course->id,
                'course_snap'       => $course->toArray(),
            ];
            $order->orderCourse()->saveAll($orderCourse);
            //订单日志
            (new OrderLogLogic())->record(OrderLogEnum::TYPE_USER, OrderLogEnum::USER_ADD_ORDER, $order->id, $userInfo['user_id']);


            Db::commit();
            return [
                'order_id'  => $order->id,
                'type'      => 'order',
            ];

        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();
        }

    }


    /**
     * @notes 订单详情
     * @param array $params
     * @param int $userId
     * @return array
     * @author cjhao
     * @date 2022/6/15 18:17
     */
    public function detail(array $params)
    {
        $detail = Order::where(['id'=>$params['id'],'user_id'=>$params['user_id']])
                ->with(['order_course'])
                ->withoutField('user_id,order_terminal,pay_time,delete_time,update_time')
                ->append(['order_status_desc','pay_way_desc','refund_status_desc','pay_btn','cancel_btn','comment_btn','del_btn'])
                ->findOrEmpty();
        if($detail->isEmpty()){
            return [];
        }
        return $detail->toArray();

    }

    /**
     * @notes 取消订单
     * @param array $params
     * @author cjhao
     * @date 2022/6/15 18:52
     */
    public function cancel(array $params)
    {
        try{

            $order = Order::where(['id'=>$params['id'],'user_id'=>$params['user_id']])
                ->field('id,order_status,pay_status,cancel_time')
                ->findOrEmpty();
            if(OrderEnum::ORDER_STSTUS_CLONE == $order->order_status){
                throw new Exception('订单已关闭');
            }

            if($order->order_status > OrderEnum::ORDER_STSTUS_WAITPAY ){
                throw new Exception('订单已支付，请联系客服取消');
            }

            //取消订单
            $order->cancel_time = time();
            $order->order_status = OrderEnum::ORDER_STSTUS_CLONE;
            $order->save();
            
            //取消订单日志
            (new OrderLogLogic())->record(OrderLogEnum::TYPE_USER, OrderLogEnum::USER_CANCEL_ORDER, $order->id, $params['user_id']);
            return true;
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * @notes 删除接口
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/10/24 16:59
     */
    public function del(array $params)
    {
        Order::destroy($params['id']);
        return true;
    }

}