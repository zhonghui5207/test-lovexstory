<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\common\model\marketing;
use app\common\enum\marketing\CouponEnum;
use app\common\model\BaseModel;
use app\common\model\course\Course;

/**
 * 优惠券模型类
 * Class Coupon
 * @package app\common\model\marketing
 */
class Coupon extends BaseModel
{

    /**
     * @notes 管理课程信息
     * @author cjhao
     * @date 2023/4/11 18:59
     */
    public function courseList()
    {
        return $this->hasMany(CouponCourse::class,'coupon_id','id');
    }

    /**
     * @notes 获取使用开始时间
     * @param $value
     * @param $data
     * @return false|string
     * @author cjhao
     * @date 2023/4/12 16:46
     */
    public function getUseTimeStartAttr($value,$data)
    {
        if($data['use_time_start']){
            return date('Y-m-d H:i:s',$data['use_time_start']);
        }
        return '';
    }


    /**
     * @notes 获取使用结束时间
     * @param $value
     * @param $data
     * @return false|string
     * @author cjhao
     * @date 2023/4/12 16:46
     */
    public function getUseTimeEndAttr($value,$data)
    {
        if($data['use_time_end']){
            return date('Y-m-d H:i:s',$data['use_time_end']);
        }
        return '';

    }

    /**
     * @notes 获取优惠券内容
     * @param $value
     * @param $data
     * @author cjhao
     * @date 2023/4/12 16:09
     */
    public function getContentAttr($value,$data)
    {
        $desc = '无使用门槛';
        if(CouponEnum::USE_CONDITION_MONEY == $data['use_condition']){
            $desc = '订单满'.$data['condition_money'].'元，减'.$data['money'].'元';
        }
        return $desc;


    }

    /**
     * @notes 获取优惠券使用时间描述
     * @param $value
     * @param $data
     * @return string
     * @author cjhao
     * @date 2023/4/12 16:39
     */
    public function getUseTimeDescAttr($value,$data)
    {
        $desc = '';
        switch ($data['use_time_type']){
            case CouponEnum::USE_TIME_TYPE_FIXED:
                $desc = date('Y-m-d H:i:s',$data['use_time_start']).'~'.date('Y-m-d H:i:s',$data['use_time_end']);
                break;
            case CouponEnum::USE_TYPE_TYPE_TODAY:
                $desc = '领取当日起'.$data['use_day'].'天内可用';
                break;
            case CouponEnum::USE_TYPE_TYPE_MORROW:
                $desc = '领取次日起'.$data['use_day'].'天内可用';
                break;
        }
        return $desc;

    }

    /**
     * @notes 获取发放数量
     * @param $value
     * @param $data
     * @return string
     * @author cjhao
     * @date 2023/4/12 18:13
     */
    public function getSendNumDescAttr($value,$data)
    {
        $desc = '';
        switch ($data['send_num_type']){
            case CouponEnum::SEND_NUM_TYPE_NOT:
                $desc = '不限';
                break;
            case CouponEnum::SEND_NUM_TYPE_FIXED:
                $desc = $data['send_num'];
                break;
        }
        return $desc;

    }


    /**
     * @notes 获取状态
     * @param $value
     * @param $data
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/12 18:09
     */
    public function getStatusDescAttr($value,$data)
    {
        return CouponEnum::getStatusDesc($data['status']);
    }

    /**
     * @notes 获取优惠券领取类型
     * @param $value
     * @param $data
     * @return string
     * @author cjhao
     * @date 2023/4/12 16:44
     */
    public function getGetTypeDescAttr($value,$data)
    {
        return CouponEnum::getGetTypeDesc($data['get_type']);
    }
}
