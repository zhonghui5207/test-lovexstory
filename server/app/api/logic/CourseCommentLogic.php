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
use app\common\enum\OrderEnum;
use app\common\model\course\CourseComment;
use app\common\model\order\Order;
use app\common\model\order\OrderCourse;
use think\Exception;
use think\facade\Db;

/**
 * 课程评论逻辑类
 * Class CourseCommentLogic
 * @package app\api\logic
 */
class CourseCommentLogic
{

    /**
     * @notes 评论数量
     * @param int $userId
     * @return array
     * @author cjhao
     * @date 2022/6/16 17:12
     */
    public function commentCount(int $userId):array
    {
        return [
            'wait_comment'      => Order::alias('O')
                                    ->join('order_course OC','O.id = OC.order_id')
                                    ->where(['O.user_id'=>$userId,'is_comment'=>0,'order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'refund_status'=>OrderEnum::REFUND_STATUS_NOT])
                                    ->count(),

            'comment'           => Order::alias('O')
                                    ->join('order_course OC','O.id = OC.order_id')
                                    ->where(['O.user_id'=>$userId,'is_comment'=>1,'order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'refund_status'=>OrderEnum::REFUND_STATUS_NOT])
                                    ->count(),
        ];
    }

    /**
     * @notes 课程评论
     * @param array $params
     * @return bool|string
     * @author cjhao
     * @date 2022/6/16 18:39
     */
    public function comment(array $params)
    {
        Db::startTrans();
        try{
            $order = Order::alias('O')
                    ->join('order_course OC','O.id = OC.order_id')
                    ->where(['user_id'=>$params['user_id'],'OC.id'=>$params['id']])
                    ->field('O.id,O.user_id,O.order_status,OC.course_id,OC.is_comment')
                    ->findOrEmpty();

            if($order->isEmpty()){
                throw new Exception('订单不存在');
            }
            if(OrderEnum::ORDER_STSTUS_COMPLETE != $order->order_status){
                throw new Exception('订单状态不能评论');
            }
            if($order->is_comment){
                throw new Exception('该课程已经评论');
            };
            $courseComment = new CourseComment();
            $courseComment->user_id = $order->user_id;
            $courseComment->order_id = $order->id;
            $courseComment->course_id = $order->course_id;
            $courseComment->course_score = $params['score'];
            $courseComment->comment = $params['comment'] ?? '';
            $courseComment->save();

            if(isset($params['image'])){
                $commentImage = [];
                foreach ($params['image'] as $image) {
                    $commentImage[] = [
                        'uri' => $image,
                    ];
                }
                $courseComment->commentImage()->saveAll($commentImage);
            }
            OrderCourse::where(['id'=>$order->id])->update(['is_comment'=>1]);
            Db::commit();
            return true;
        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();
        }

    }

    /**
     * @notes 获取评论商品信息
     * @param array $params
     * @return array
     * @author cjhao
     * @date 2022/6/21 16:21
     */
    public function commentGoodsInfo(array $params)
    {
        $order = OrderCourse::alias('OC')
                ->join('order O','OC.order_id = O.id')
                ->where(['O.user_id'=>$params['user_id'],'OC.id'=>$params['id']])
                ->field('OC.id,OC.order_id,OC.course_id,OC.course_snap')
                ->findOrEmpty();
        if($order->isEmpty()){
            return [];
        }
        return $order->toArray();
    }
}