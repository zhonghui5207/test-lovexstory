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
namespace app\adminapi\lists\order;
use app\common\enum\OrderEnum;
use app\common\lists\BaseDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\order\Order;
use app\common\model\order\OrderCourse;
use app\common\model\order\OrderLog;
use app\common\service\FileService;

/**
 * 订单列表类
 * Class OrderLists
 * @package app\adminapi\lists\order
 */
class OrderLists extends BaseDataLists implements ListsSearchInterface
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = Order::alias('O')
                ->leftjoin('user U','O.user_id = U.id')
                ->where($this->setSearch())
                ->with(['order_course'])
                ->append(['order_status_desc','refund_status_desc','pay_status_desc','pay_way_desc'])
                ->field('O.id,O.sn,O.user_id,U.sn as user_sn,U.avatar,U.nickname,O.pay_way,O.pay_status,O.order_status,O.order_status,O.pay_way,O.total_amount,O.order_amount,O.refund_status,O.shop_remark,O.create_time,O.type')
                ->order('O.id desc')
                ->limit($this->limitOffset, $this->limitLength)
                ->select()
                ->toArray();

        foreach ($lists as &$order)
        {
            $order['avatar'] = FileService::getFileUrl($order['avatar']);
//            if ($order['type'] == OrderEnum::ORDER_TYPE_QUESTIONBANK) {
//                $order['order_course'][0]['course_snap']['cover'] = FileService::getFileUrl('resource/image/adminapi/default/questionbank_order.png');
//                $order['order_course'][0]['course_snap']['sell_price'] = $order['order_course'][0]['course_snap']['pay_amount'];
//            }
        }

        return $lists;
    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        $count = Order::alias('O')
            ->leftjoin('user U','O.user_id = U.id')
            ->where($this->setSearch())
            ->count();

        return $count;
    }

    /**
     * @notes 设置搜索条件
     * @return array
     * @author 令狐冲
     * @date 2021/7/7 19:44
     */
    public function setSearch(): array
    {
        $where = [];
        if(isset($this->params['type']) && $this->params['type']){
            $where[] = ['O.order_status','=',$this->params['type']];
            if(OrderEnum::ORDER_STSTUS_WAITPAY == $this->params['type']){
                $where[] = ['O.pay_status','=',OrderEnum::PAY_STATUS_WAIT];
            }
        }
        if(isset($this->params['sn']) && $this->params['sn']){
            $where[] = ['O.sn','like','%'.$this->params['sn'].'%'];
        }
        if(isset($this->params['keyword']) && $this->params['keyword']){
            $where[] = ['U.nickname','like','%'.$this->params['keyword'].'%'];
        }
        if(isset($this->params['pay_status']) && '' != $this->params['pay_status']){
            $where[] = ['pay_status','=',$this->params['pay_status']];
        }
        if(isset($this->params['refund_status']) && $this->params['refund_status']){
            $where[] = ['refund_status','=',$this->params['refund_status']];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['O.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['O.create_time','<=',strtotime($this->params['end_time'])];
        }
        if(isset($this->params['course_type']) && '' != $this->params['course_type']){

            $orderIds = OrderCourse::where('course_snap->type',(int)$this->params['course_type'])
                ->setFieldType(['course_snap->type' => 'int'])
                ->field('order_id')
                ->select()->toArray();
            $orderIds = array_column($orderIds,'order_id');
            $where[] = ['O.id','in',$orderIds];
        }
        return $where;
    }
}