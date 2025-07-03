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
use app\common\enum\marketing\CouponListEnum;
use app\common\model\BaseModel;
use app\common\model\user\User;

/**
 * 用户优惠券模型类
 * Class Coupon
 * @package app\common\model\marketing
 */
class CouponList extends BaseModel
{


    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }


    /**
     * @notes 优惠券
     * @return \think\model\relation\HasOne
     * @author cjhao
     * @date 2023/4/13 10:52
     */
    public function coupon()
    {
        return $this->hasOne(Coupon::class,'id','coupon_id')->bind(['name']);
    }

    /**
     * @notes 获取失效时间
     * @param $value
     * @param $data
     * @return false|string
     * @author cjhao
     * @date 2023/4/13 10:59
     */
    public function getInvalidTimeDescAttr($value,$data)
    {
        return date('Y-m-d H:i:s',$data['invalid_time']);
    }

    /**
     * @notes 获取使用时间
     * @param $value
     * @param $data
     * @return false|string
     * @author cjhao
     * @date 2023/4/13 11:05
     */
    public function getUseTimeDescAttr($value,$data)
    {
        if($data['use_time']){
            return date('Y-m-d H:i:s',$data['use_time']);
        }
        return '-';
    }

    /**
     * @notes 获取优惠券状态
     * @param $value
     * @param $data
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/13 11:06
     */
    public function getStatusDescAttr($value,$data)
    {
        return CouponListEnum::getStatusDesc($data['status']);
    }


    /**
     * @notes 获取优惠券获取的渠道
     * @param $value
     * @param $data
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/13 11:13
     */
    public function getChannelDescAttr($value,$data)
    {
        return CouponListEnum::getChannelDesc($data['channel']);
    }

    /**
     * @notes 作废按钮
     * @param $value
     * @param $data
     * @return bool
     * @author cjhao
     * @date 2023/4/13 12:15
     */
    public function getCancelBtnAttr($value,$data)
    {
        if(CouponListEnum::STATUS_NOT_USE == $data['status']){
            return true;
        }
        return false;
    }
}
