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
namespace app\api\lists;
use app\common\enum\marketing\CouponEnum;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;

/**
 * 优惠券列表类
 * Class CouponLists
 * @package app\api\lists
 */
class CouponLists extends BaseApiDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $couponLists = Coupon::where($this->seachWhere())
            ->append(['content','use_time_desc'])
            ->order(['id'=>'desc'])
            ->select();

        $userCouponIds = CouponList::where(['user_id'=>$this->userId])->column('coupon_id');
        $lists = [];
        foreach ($couponLists as $coupon)
        {
            $isGet = false;

            //是否已领取
            if(in_array($coupon->id,$userCouponIds)){
                $isGet = true;
            }
            //使用时间
//            switch ($coupon->use_time_type){
//                case CouponEnum::USE_TIME_TYPE_FIXED:
//                    $useTimeDesc = $coupon->use_time_start.'~'.$coupon->use_time_end;
//                    break;
//                case CouponEnum::USER_SCOPE_COUSE_USABLE:
//                    $useTimeDesc = "领券当日起".$coupon->use_day."天内可用";
//                    break;
//                case CouponEnum::USER_SCOPE_COUSE_NUSABLE:
//                    $useTimeDesc = "领券次日起".$coupon->use_day."天内可用";
//                    break;
//            }
            //发放数量
            $sendNum = 0;
            switch ($coupon->send_num_type){
                case CouponEnum::SEND_NUM_TYPE_NOT:
                    $sendNum = true;
                    break;
                case CouponEnum::SEND_NUM_TYPE_FIXED:
                    $sendNum = $coupon->send_num;
                    break;
            }
            //剩余数量
            $receivedNum = CouponList::where(['id'=>$coupon->id])->count();//已领取数量
            $surplusNum = $sendNum - $receivedNum;
            if ($sendNum === true) {
                $surplusNum = true;
            }
            $getNum = 0;
            switch ($coupon->get_num_type){
                case CouponEnum::GET_NUM_TYPE_NO:
                    $getNum = $surplusNum;
                    break;
                case CouponEnum::GET_NUM_TYPE_LIMIT:
                    $couponCount = CouponList::where(['id'=>$coupon->id,'user_id'=>$this->userId])->count();
                    $getNum = $coupon->get_num - $couponCount;
                    $getNum = $getNum > $surplusNum ? $surplusNum : $getNum;
                    break;
                case CouponEnum::GET_NUM_TYPE_LIMIT_TODAY:
                    $couponCount = CouponList::where(['id'=>$coupon->id,'user_id'=>$this->userId])->whereDay('create_time')->count();
                    $getNum = $coupon->get_num - $couponCount;
                    $getNum = $getNum > $surplusNum ? $surplusNum : $getNum;
                    break;
            }
            $getNum = $getNum < 0 ?: $getNum;

            $lists[] = [
                'name'              => $coupon->name,
                'money'             => $coupon->money,
                'use_scope'         => $coupon->use_scope,
                'use_scope_desc'    => CouponEnum::getUseScopeDesc($coupon->use_scope),
                'use_time_desc'     => $coupon->use_time_desc,
                'get_num'           => $getNum,
                'is_get'            => $isGet,
                'id'                => $coupon->id,
                'content'           => $coupon->content,
            ];
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
        return Coupon::where($this->seachWhere())
            ->count();
    }

    /**
     * @notes 设置条件
     * @return array
     * @author cjhao
     * @date 2023/5/4 11:42
     */
    public function seachWhere()
    {
        $where[] = ['status','=',CouponEnum::STATUS_ING];
        $where[] = ['get_type','=',CouponEnum::GET_TYPE_USER];
        $where[] = ['receive_time_start','<',time()];
        $where[] = ['receive_time_end','>',time()];
        return $where;

    }
}