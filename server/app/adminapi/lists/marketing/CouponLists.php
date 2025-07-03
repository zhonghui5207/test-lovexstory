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

namespace app\adminapi\lists\marketing;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\marketing\CouponEnum;
use app\common\enum\marketing\CouponListEnum;
use app\common\lists\ListsExtendInterface;
use app\common\lists\ListsSearchInterface;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;


/**
 * 优惠券列表类
 * Class CouponLists
 * @package app\adminapi\lists\auth
 */
class CouponLists extends BaseAdminDataLists implements  ListsSearchInterface,ListsExtendInterface
{



    /**
     * @notes 设置搜索条件
     * @return array
     * @author cjhao
     * @date 2023/4/12 16:13
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name'],
            '=' => ['get_type','use_scope','status']
        ];
    }



    /**
     * @notes 优惠券列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2023/4/12 16:13
     */
    public function lists(): array
    {

        $couponLists = Coupon::where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id'=>'desc'])
            ->withoutField('update_time,delete_time')
            ->append(['content','use_time_start','use_time_end','get_type_desc','status_desc','use_time_desc','use_condition','send_num_desc'])
            ->select();

        $couponIds = array_column($couponLists->toArray(),'id');
        //已领取的优惠券
        $getCouponList = CouponList::where(['coupon_id'=>$couponIds])
            ->where(['status'=>CouponListEnum::STATUS_NOT_USE])
            ->group('coupon_id')
            ->column('count(id) as num','coupon_id');
        //已使用的优惠券
        $useCouponList = CouponList::where(['coupon_id'=>$couponIds])
            ->where(['status'=>CouponListEnum::STATUS_USE])
            ->group('coupon_id')
            ->column('count(id) as num','coupon_id');
        //优惠券列表
        foreach ($couponLists as $coupon) {

            $coupon->get_coupon_num = $getCouponList[$coupon->id] ?? 0;
            $coupon->use_coupon_num = $useCouponList[$coupon->id] ?? 0;
            $coupon->use_rate = '0%';
            if($coupon->get_coupon_num > 0){
                $coupon->use_rate = round($coupon->use_coupon_num/$coupon->get_coupon_num * 100,2).'%';
            }

            //领券时间
            $coupon['receive_time_start'] = date('Y-m-d H:i:s',$coupon['receive_time_start']);
            $coupon['receive_time_end'] = date('Y-m-d H:i:s',$coupon['receive_time_end']);
        }

        return $couponLists->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author cjhao
     * @date 2023/4/12 16:13
     */
    public function count(): int
    {
        return Coupon::where($this->searchWhere)->count();
    }



    public function extend()
    {
        $allNum = Coupon::count();
        $notStartNum = Coupon::where(['status'=>CouponEnum::STATUS_NOT_START])->count();
        $ingNum = Coupon::where(['status'=>CouponEnum::STATUS_ING])->count();
        $endNum = Coupon::where(['status'=>CouponEnum::STATUS_END])->count();
        return [
            'all_num'           => $allNum,
            'not_start_num'     => $notStartNum,
            'ing_num'           => $ingNum,
            'end_num'           => $endNum,
        ];
    }
}