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

use app\common\enum\CourseEnum;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\enum\OrderRefundEnum;
use app\common\enum\RefundEnum;
use app\common\logic\CourseLogic;
use app\common\logic\OrderLogLogic;
use app\common\logic\RefundLogic;
use app\common\model\course\StudyCourse;
use app\common\model\order\Order;
use app\common\model\order\OrderLog;
use think\Exception;
use think\facade\Db;

/**
 * 订单逻辑类
 * Class OrderLogic
 * @package app\adminapi\logic\order
 */
class OrderLogic
{
    /**
     * @notes 列表其他数据
     * @return array
     * @author cjhao
     * @date 2022/6/16 16:35
     */
    public function otherLists():array
    {
        $data = [
            'all_num'           => Order::count(),
            'waitpay_num'       => Order::where(['pay_status'=>OrderEnum::PAY_STATUS_WAIT,'order_status'=>OrderEnum::ORDER_STSTUS_WAITPAY])->count(),
            'complete_num'      => Order::where(['order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE])->count(),
            'close_num'         => Order::where(['order_status'=>OrderEnum::ORDER_STSTUS_CLONE])->count(),
            'pay_status'        => OrderEnum::getPayStatusDesc(),
            'order_status'      => OrderEnum::getOrderStatusDesc(),
            'refund_status'     => OrderEnum::getRefundStatusDesc(),
            'couser_type'       => CourseEnum::getCouserTypeDesc(),
        ];
        return $data;

    }

    /**
     * @notes 订单详情
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2022/6/16 16:24
     */
    public function detail(int $id)
    {
        $detail = Order::field('id,sn,user_id,order_terminal,order_status,pay_status,pay_way,pay_time,total_amount,order_amount,refund_status,cancel_time,create_time,shop_remark,discount_amount')
            ->with(['user', 'order_course', 'order_log'])
            ->append(['order_status_desc', 'pay_status_desc', 'refund_status_desc', 'pay_way_desc', 'cancel_btn', 'refund_btn','order_log'=>['operator','channel_desc']])
            ->findOrEmpty($id);

        return $detail->toArray();

    }

    /**
     * @notes 商家备注
     * @param array $params
     * @author cjhao
     * @date 2022/6/16 12:07
     */
    public function shopRemark(array $params)
    {
        Order::where(['id' => $params['id']])->update(['shop_remark' => $params['remark']]);
        (new OrderLogLogic())->record( OrderLogEnum::TYPE_ADMIN,OrderLogEnum::PLATFORM_ORDER_REMARKS,$params['id'],$params['admin_id']);
        return ;
    }


    /**
     * @notes 取消订单
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2022/6/16 15:19
     */
    public function cancel(int $id,int $adminId)
    {
        try {
            $order = Order::where(['id'=>$id])
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
            (new OrderLogLogic())->record(OrderLogEnum::TYPE_ADMIN, OrderLogEnum::PLATFORM_CANCEL_ORDER,$order->id,$adminId);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @notes 发起退款（不记录售后，只改变订单状态和记录退款信息）
     * @param array $params
     * @return bool|string
     * @author cjhao
     * @date 2022/6/22 18:54
     */
    public function refund(array $params)
    {
        try{
            Db::startTrans();
            $order = Order::with(['order_course'])->findOrEmpty($params['id']);
            if($order->isEmpty()){
                throw new Exception('订单不存在');
            }
            if(OrderEnum::ORDER_STSTUS_COMPLETE != $order->order_status){
                throw new Exception('订单不能退款');
            }
            //发起退款
            RefundLogic::refund($order,OrderRefundEnum::REFUND_TYPE_PLATFORM_REFUND,$params['admin_id']);
           
            (new OrderLogLogic())->record(OrderLogEnum::TYPE_ADMIN,OrderLogEnum::PLATFORM_REFUND_ORDER,$order->id,$params['admin_id']);

            //回收课程
            $courseId = $order->order_course[0]['course_id'];
            CourseLogic::courseStatistics($order->user_id,$courseId,$order->id,0);
            //更新订单状态
            Order::where(['id'=>$order['id']])
                ->update(['refund_status'=> OrderEnum::REFUND_STATUS_ALL,'refund_time'=>time()]);


            Db::commit();
            return true;
        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();
        }


    }
}