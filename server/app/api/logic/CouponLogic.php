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

namespace app\api\logic;


use app\common\enum\marketing\CouponEnum;
use app\common\logic\BaseLogic;
use app\common\model\course\Course;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;
use app\common\model\order\Order;

class CouponLogic extends BaseLogic
{
    /**
     * @notes 领取优惠券
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/8/23 11:09 上午
     */
    public function receive($params)
    {
        // 优惠券信息
        $coupon = (new Coupon())->findOrEmpty(intval($params['id']))->toArray();

        // 计算出券最后可用时间
        $invalid_time = 0;
        switch ($coupon['use_time_type']) {
            case CouponEnum::USE_TIME_TYPE_FIXED:
                $invalid_time = strtotime($coupon['use_time_end']);
                break;
            case CouponEnum::USE_TYPE_TYPE_TODAY:
                $invalid_time = time() + ($coupon['use_day'] * 86400);
                break;
            case CouponEnum::USE_TYPE_TYPE_MORROW:
                $time = strtotime(date('Y-m-d', strtotime("+1 day")));
                $invalid_time = $time + ($coupon['use_day'] * 86400);
        }

        // 发放优惠券
        CouponList::create([
            'channel'      => CouponEnum::GET_TYPE_USER,
            'user_id'      => $params['user_id'],
            'coupon_id'    => $coupon['id'],
            'invalid_time' => $invalid_time,
//            'coupon_code'  => create_code(),
        ]);

        // 汽泡足迹
//        event('Footprint', ['type' => FootprintEnum::GET_COUPONS, 'user_id' => $params['user_id']]);

        return true;
    }

    /**
     * @notes 订单结算页优惠券列表
     * @param $params
     * @return array|array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/2/24 6:42 下午
     */
    public function orderCoupon($params)
    {
        $order = Order::with('order_course')
            ->where(['id'=>$params['order_id']])
            ->findOrEmpty()
            ->toArray();

        $lists = Coupon::alias('c')
            ->join('coupon_list cl','c.id = cl.coupon_id')
            ->field('c.id,cl.id as coupon_list_id,cl.coupon_id,c.name,c.money,c.use_condition,c.condition_money,c.use_time_type,c.use_time_start,c.use_time_end,c.use_day,c.use_scope,cl.invalid_time,cl.create_time')
            ->with(['course_list'])
            ->append(['content','use_time_desc'])
            ->where(['cl.user_id'=>$params['user_id'],'cl.status'=>CouponEnum::USE_STATUS_NOT])
            ->where('cl.invalid_time','>',time())
            ->order(['cl.id'=>'desc'])
            ->select()
            ->toArray();
        $result = [
            'usable' => [],
            'unusable' => [],
        ];
        foreach ($lists as &$list) {
            $list['use_time_desc'] = '有效期至'.date('Y-m-d H:i:s',$list['invalid_time']);

            if ($order['order_amount'] <= 0) {
                $list['coupon_describe'] = '订单商品不满足使用要求';
                $result['unusable'][] = $list;
                continue;
            }
            if ($list['use_condition'] == CouponEnum::USE_CONDITION_MONEY) {
                if ($order['order_amount'] < $list['condition_money']) {
                    $list['coupon_describe'] = '未满足规定的订单金额';
                    $result['unusable'][] = $list;
                    continue;
                }
            }
            if ($list['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED && (strtotime($list['use_time_start']) > time() || strtotime($list['use_time_end']) < time())) {
                $list['coupon_describe'] = '未满足优惠券使用时间';
                $result['unusable'][] = $list;
                continue;
            }
            if ($list['use_time_type'] == CouponEnum::USE_TYPE_TYPE_TODAY && $list['invalid_time'] < time()) {
                $list['coupon_describe'] = '未满足优惠券使用时间';
                $result['unusable'][] = $list;
                continue;
            }
            if ($list['use_time_type'] == CouponEnum::USE_TYPE_TYPE_MORROW) {
                $start_time = strtotime(date("Y-m-d", strtotime("+1 day", strtotime($list['create_time']))).' 00:00:00');
                if ($start_time > time() || $list['invalid_time'] < time()) {
                    $list['coupon_describe'] = '未满足优惠券使用时间';
                    $result['unusable'][] = $list;
                    continue;
                }
            }
            if($list['use_scope'] == CouponEnum::USER_SCOPE_COUSE_USABLE) {
                $course_ids = array_column($list['course_list'],'course_id');
                if (!in_array($order['order_course'][0]['course_id'], $course_ids)) {
                    $list['coupon_describe'] = '课程不适用';
                    $result['unusable'][] = $list;
                    continue;
                }
            }
            if($list['use_scope'] == CouponEnum::USER_SCOPE_COUSE_NUSABLE) {
                $course_ids = array_column($list['course_list'],'course_id');
                if (in_array($order['order_course'][0]['course_id'], $course_ids)) {
                    $list['coupon_describe'] = '指定课程不可用';
                    $result['unusable'][] = $list;
                    continue;
                }
            }

            $result['usable'][] = $list;
        }

        //可使用优惠券按金额大小排序
        $sort_money = array_column($result['usable'],'money');
        array_multisort($sort_money,SORT_DESC,$result['usable']);

        return $result;
    }


    /**
     * @notes 课程详情优惠券
     * @param $params
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/8/28 9:47 上午
     */
    public function courseCoupon($params)
    {
        $lists = Coupon::field('id,name,money,use_condition,condition_money,use_time_type,use_time_start,use_time_end,use_day,use_scope,get_num_type,get_num,send_num_type,send_num')
            ->with(['course_list'])
            ->append(['content','use_time_desc'])
            ->where(['status'=>CouponEnum::STATUS_ING,'get_type'=>CouponEnum::GET_TYPE_USER])
            ->where('receive_time_start','<',time())
            ->where('receive_time_end','>',time())
            ->order(['id'=>'desc'])
            ->select()
            ->toArray();

        $userCouponIds = CouponList::where(['user_id'=>$params['user_id']])->column('coupon_id');

        $result = [];
        foreach ($lists as $list) {
            if($list['use_scope'] == CouponEnum::USER_SCOPE_COUSE_USABLE) {
                $course_ids = array_column($list['course_list'],'course_id');
                if (!in_array($params['course_id'], $course_ids)) {
                    continue;
                }
            }
            if($list['use_scope'] == CouponEnum::USER_SCOPE_COUSE_NUSABLE) {
                $course_ids = array_column($list['course_list'],'course_id');
                if (in_array($params['course_id'], $course_ids)) {
                    continue;
                }
            }

            $isGet = false;

            //是否已领取
            if(in_array($list['id'],$userCouponIds)){
                $isGet = true;
            }

            //发放数量
            $sendNum = 0;
            switch ($list['send_num_type']){
                case CouponEnum::SEND_NUM_TYPE_NOT:
                    $sendNum = true;
                    break;
                case CouponEnum::SEND_NUM_TYPE_FIXED:
                    $sendNum = $list['send_num'];
                    break;
            }
            //剩余数量
            $receivedNum = CouponList::where(['id'=>$list['id']])->count();//已领取数量
            $surplusNum = $sendNum - $receivedNum;
            if ($sendNum === true) {
                $surplusNum = true;
            }
            $getNum = 0;
            switch ($list['get_num_type']){
                case CouponEnum::GET_NUM_TYPE_NO:
                    $getNum = $surplusNum;
                    break;
                case CouponEnum::GET_NUM_TYPE_LIMIT:
                    $couponCount = CouponList::where(['id'=>$list['id'],'user_id'=>$params['user_id']])->count();
                    $getNum = $list['get_num'] - $couponCount;
                    $getNum = $getNum > $surplusNum ? $surplusNum : $getNum;
                    break;
                case CouponEnum::GET_NUM_TYPE_LIMIT_TODAY:
                    $couponCount = CouponList::where(['id'=>$list['id'],'user_id'=>$params['user_id']])->whereDay('create_time')->count();
                    $getNum = $list['get_num'] - $couponCount;
                    $getNum = $getNum > $surplusNum ? $surplusNum : $getNum;
                    break;
            }
            $getNum = $getNum < 0 ?: $getNum;

            $result[] = [
                'name'              => $list['name'],
                'money'             => $list['money'],
                'use_scope'         => $list['use_scope'],
                'use_scope_desc'    => CouponEnum::getUseScopeDesc($list['use_scope']),
                'use_time_desc'     => $list['use_time_desc'],
                'get_num'           => $getNum,
                'is_get'            => $isGet,
                'id'                => $list['id'],
                'content'         => $list['content'],
            ];
        }

        return $result;
    }
}