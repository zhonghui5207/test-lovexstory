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
namespace app\common\model\order;
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\model\BaseModel;
use app\common\model\user\User;
use think\model\concern\SoftDelete;
use think\Validate;

/**
 * 订单模型类
 * Class Order
 * @package app\common\model\order
 */
class Order extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';


    public function orderCourse()
    {
        return $this->hasMany(OrderCourse::class,'order_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')
            ->field('id,nickname,sn,avatar');
    }

    public function orderLog()
    {
        return $this->hasMany(OrderLog::class,'order_id')
                ->field('id,type,channel,order_id,operator_id,content,create_time');
    }

    /**
     * @notes 支付方式
     * @param $value
     * @param $data
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/15 16:24
     */
    public function getPayWayDescAttr($value,$data)
    {
        return PayEnum::getPayTypeDesc($data['pay_way']);
    }

    /**
     * @notes 支付状态
     * @param $value
     * @param $data
     * @return mixed
     * @author cjhao
     * @date 2022/6/15 16:29
     */
    public function getPayStatusDescAttr($value,$data)
    {
        return OrderEnum::getPatStatusDesc($data['pay_status']);
    }

    /**
     * @notes 订单状态
     * @param $value
     * @param $data
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/15 15:55
     */
    public function getOrderStatusDescAttr($value,$data)
    {
        return OrderEnum::getOrderStatusDesc($data['order_status']);
    }

    /**
     * @notes 退款状态
     * @param $value
     * @param $data
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/12/8 14:20
     */
    public function getRefundStatusDescAttr($value,$data)
    {
        return OrderEnum::getRefundStatusDesc($data['refund_status']);
    }


//    public function getUserBtnAttr($value,$data)
//    {
//
//    }

    /**
     * @notes 去支付按钮
     * @param $value
     * @param $data
     * @return bool
     * @author cjhao
     * @date 2022/6/15 17:38
     */
    public function getPayBtnAttr($value,$data)
    {
        if(( OrderEnum::ORDER_STSTUS_WAITPAY == $data['order_status'] && 0 == $data['cancel_time']) || ($data['cancel_time'] > time() &&  OrderEnum::ORDER_STSTUS_WAITPAY == $data['order_status']))
        {
            return true;
        }
        return false;
    }

    /**
     * @notes 取消支付按钮
     * @param $value
     * @param $data
     * @return bool
     * @author cjhao
     * @date 2022/6/15 17:35
     */
    public function getCancelBtnAttr($value,$data)
    {
        if(OrderEnum::PAY_STATUS_WAIT == $data['pay_status'] && $data['cancel_time'] > time()){
            return true;
        }
        return false;
    }

    /**
     * @notes 删除按钮
     * @param $value
     * @param $data
     * @return bool
     * @author cjhao
     * @date 2022/6/15 17:40
     */
    public function getDelBtnAttr($value,$data)
    {
        if(OrderEnum::ORDER_STSTUS_CLONE == $data['order_status']) {
            return true;
        }
        return false;
    }

    /**
     * @notes 退款按钮
     * @param $value
     * @param $data
     * @return bool
     * @author cjhao
     * @date 2022/6/16 14:35
     */
    public function getRefundBtnAttr($value,$data)
    {
        if(OrderEnum::ORDER_STSTUS_COMPLETE == $data['order_status'] && OrderEnum::REFUND_STATUS_NOT == $data['refund_status']){
            return true;
        }
        return false;
    }

    /**
     * @notes 去评论按钮
     * @param $value
     * @param $data
     * @return bool
     * @author cjhao
     * @date 2022/6/15 17:56
     */
    public function getCommentBtnAttr($value,$data)
    {

        $orderCourse = $this->getData('order_course')->toarray();
        $btn = false;
        foreach ($orderCourse as $course){
            if(0 == $course['is_comment'] && OrderEnum::ORDER_STSTUS_COMPLETE == $data['order_status']){
                $btn = true;
            }
        }
        return $btn;
    }

    public function getPayTimeAttr($value,$data)
    {
        if($value){
            return date('Y-m-d H:i:s',$value);
        }
        return $value;
    }
}