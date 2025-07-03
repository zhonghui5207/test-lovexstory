<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\api\validate;


use app\common\enum\marketing\CouponEnum;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;
use app\common\validate\BaseValidate;

class CouponValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'order_id' => 'require',
        'course_id' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'order_id.require' => '参数缺失',
        'course_id.require' => '参数缺失',
    ];


    public function sceneReceive()
    {
        return $this->only(['id'])
            ->append('id','checkReceive');
    }

    public function sceneOrderCoupon()
    {
        return $this->only(['order_id']);
    }

    public function sceneCourseCoupon()
    {
        return $this->only(['course_id']);
    }

    /**
     * @notes 校验领取优惠券
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/8/23 11:03 上午
     */
    public function checkReceive($value,$rule,$data)
    {
        $coupon = Coupon::where('id',$value)->findOrEmpty()->toArray();
        if (!$coupon or $coupon['status'] == CouponEnum::STATUS_NOT_START) {
            return '优惠券尚未开放领取，敬请期待';
        }
        if (!$coupon or $coupon['status'] == CouponEnum::STATUS_END) {
            return '领取失败，优惠券活动已结束';
        }
        if ($coupon['receive_time_start'] > time() || $coupon['receive_time_end'] < time()) {
            return '不在领取时间内，无法领取';
        }

        if ($coupon['send_num_type'] == CouponEnum::SEND_NUM_TYPE_FIXED) {
            $send_total = CouponList::where(['coupon_id'=>$coupon['id']])->count();
            if (($coupon['send_num'] - $send_total) <= 0) {
                return '优惠券已被领完';
            }
        }

        switch ($coupon['get_num_type']) {
            case CouponEnum::GET_NUM_TYPE_LIMIT:
                $total = (new CouponList())
                    ->where('user_id', '=', $data['user_id'])
                    ->where('coupon_id', '=', $coupon['id'])
                    ->count();
                if ($total >= $coupon['get_num']) {
                    return '领取失败，超过领取限制';
                }
                break;
            case CouponEnum::GET_NUM_TYPE_LIMIT_TODAY:
                $total = (new CouponList())
                    ->where('coupon_id', '=', $coupon['id'])
                    ->where('user_id', '=', $data['user_id'])
                    ->whereDay('create_time')
                    ->count();
                if ($total >= $coupon['get_num']) {
                    return '领取失败，超过今天领取限制';
                }
                break;
        }

        return true;
    }
}