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

namespace app\api\lists;


use app\api\lists\BaseApiDataLists;
use app\common\enum\marketing\CouponEnum;
use app\common\lists\ListsExtendInterface;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;

class CouponUserLists extends BaseApiDataLists implements ListsExtendInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/2/23 10:19 上午
     */
    public function where()
    {
        $where[] = ['cl.user_id','=',$this->userId];
        if (isset($this->params['status']) && $this->params['status'] != '') {
            switch ($this->params['status']) {
                case 1:
                    $where[] = ['cl.status','=',CouponEnum::USE_STATUS_NOT];
                    break;
                case 2:
                    $where[] = ['cl.status','=',CouponEnum::USE_STATUS_OK];
                    break;
                case 3:
                    $where[] = ['cl.status','in',[CouponEnum::USE_STATUS_EXPIRE,CouponEnum::USE_STATUS_VOID]];
                    break;
            }

        }
        if (isset($this->params['course_id']) && $this->params['course_id'] != '') {
            $allow_ids = Coupon::alias('c')
                ->leftjoin('coupon_course cc', 'cc.coupon_id = c.id')
                ->where(['c.status'=>CouponEnum::STATUS_ING,'c.use_scope'=>CouponEnum::USER_SCOPE_COUSE_USABLE,'cc.course_id'=>$this->params['course_id']])
                ->column('c.id');
            $not_ids = Coupon::alias('c')
                ->leftjoin('coupon_course cc', 'cc.coupon_id = c.id')
                ->where(['c.status'=>CouponEnum::STATUS_ING,'c.use_scope'=>CouponEnum::USER_SCOPE_COUSE_NUSABLE,'cc.course_id'=>$this->params['course_id']])
                ->column('c.id');
            $ids = array_merge($allow_ids,$not_ids);
            $where[] = ['c.id','in',$ids];
        }

        return $where;
    }

    /**
     * @notes 用户优惠券列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/2/23 10:20 上午
     */
    public function lists(): array
    {
        $lists = Coupon::alias('c')
            ->join('coupon_list cl','c.id = cl.coupon_id')
            ->field('cl.id,cl.coupon_id,c.name,c.money,c.use_condition,c.condition_money,c.use_time_type,c.use_time_start,c.use_time_end,c.use_day,c.use_scope,cl.create_time,cl.invalid_time,cl.status')
            ->append(['content','use_time_desc'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['cl.id'=>'desc'])
            ->select()
            ->toArray();

        foreach ($lists as $key=>&$list) {
            $list['use_time_desc'] = '有效期至'.date('Y-m-d H:i:s',$list['invalid_time']);
            $list['is_countdown'] = 0;
            
            if ($list['status'] == CouponEnum::USE_STATUS_NOT) {
                if ($list['invalid_time'] < time()) {
                    unset($lists[$key]);
                    continue;
                }
                if ($list['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED) {
                    $time = strtotime($list['use_time_end']) - time();
                    if (0 < $time && $time < (24 * 60 * 60)) {
                        $list['use_time_desc'] = '即将过期，仅剩余'.date('H:i:s',time() + $time);
                        $list['is_countdown'] = 1;
                    }
                }
                if ($list['use_time_type'] == CouponEnum::USE_TYPE_TYPE_TODAY) {
                    $time = strtotime($list['create_time']) + ($list['use_day'] * 24 * 60 * 60) - time();
                    if (0 < $time && $time < (24 * 60 * 60)) {
                        $list['use_time_desc'] = '即将过期，仅剩余'.date('H:i:s',time() + $time);
                        $list['is_countdown'] = 1;
                    }
                }
                if ($list['use_time_type'] == CouponEnum::USE_TYPE_TYPE_MORROW) {
                    $time = strtotime($list['create_time']) + (($list['use_day'] + 1) * 24 * 60 * 60) - time();
                    if (0 < $time && $time < (24 * 60 * 60)) {
                        $list['use_time_desc'] = '即将过期，仅剩余'.date('H:i:s',time() + $time);
                        $list['is_countdown'] = 1;
                    }
                }
            }
        }

        return array_values($lists);
    }

    /**
     * @notes 用户优惠券数量
     * @return int
     * @author ljj
     * @date 2023/2/23 10:20 上午
     */
    public function count(): int
    {
        return Coupon::alias('c')
            ->join('coupon_list cl','c.id = cl.coupon_id')
            ->where($this->where())
            ->count();
    }

    /**
     * @notes 统计数据
     * @return array
     * @author ljj
     * @date 2023/8/23 6:28 下午
     */
    public function extend()
    {
        $model = new CouponList();
        // 可使用的优惠券数量
        $detail['normal'] = $model->alias('cl')->join('coupon c','c.id = cl.coupon_id')->where(['cl.user_id'=>$this->userId,'cl.status'=>CouponEnum::USE_STATUS_NOT])->count();
        // 已使用的优惠券数量
        $detail['use'] = $model->alias('cl')->join('coupon c','c.id = cl.coupon_id')->where(['cl.user_id'=>$this->userId,'cl.status'=>CouponEnum::USE_STATUS_OK])->count();
        // 已失效的优惠券数量
        $detail['invalid'] = $model->alias('cl')->join('coupon c','c.id = cl.coupon_id')->where(['cl.user_id'=>$this->userId,'cl.status'=>[CouponEnum::USE_STATUS_EXPIRE, CouponEnum::USE_STATUS_VOID]])->count();

        return $detail;
    }
}