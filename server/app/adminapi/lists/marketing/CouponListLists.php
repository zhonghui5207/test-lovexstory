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
namespace app\adminapi\lists\marketing;
use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\marketing\CouponListEnum;
use app\common\lists\ListsExtendInterface;
use app\common\model\auth\Admin;
use app\common\model\marketing\CouponList;

/**
 * 用户优惠券列表类
 * Class CouponListLists
 * @package app\adminapi\lists\marketing
 */
class CouponListLists extends BaseAdminDataLists implements ListsExtendInterface
{

    /**
     * @notes 扩展字段
     * @return mixed
     * @author 令狐冲
     * @date 2021/7/21 17:45
     */
    public function extend()
    {

        $allCount = CouponList::alias('CL')->join('user U','U.id = CL.user_id')->join('coupon C','C.id = CL.coupon_id')->count();
        $notUseCount = CouponList::alias('CL')->join('user U','U.id = CL.user_id')->join('coupon C','C.id = CL.coupon_id')->where(['CL.status'=>CouponListEnum::STATUS_NOT_USE])->count();
        $useCount = CouponList::alias('CL')->join('user U','U.id = CL.user_id')->join('coupon C','C.id = CL.coupon_id')->where(['CL.status'=>CouponListEnum::STATUS_USE])->count();
        $overtimeCount = CouponList::alias('CL')->join('user U','U.id = CL.user_id')->join('coupon C','C.id = CL.coupon_id')->where(['CL.status'=>CouponListEnum::STATUS_OVERTIME])->count();
        $cancelCount = CouponList::alias('CL')->join('user U','U.id = CL.user_id')->join('coupon C','C.id = CL.coupon_id')->where(['CL.status'=>CouponListEnum::STATUS_CANCEL])->count();

        return [
            'all_count' => $allCount,
            'not_use_count' => $notUseCount,
            'use_count' => $useCount,
            'overtime_count' => $overtimeCount,
            'cancel_count' => $cancelCount,
        ];
    }

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {

        $where = $this->setSearch();
        $couponList = CouponList::alias('CL')->where($where)
            ->join('user U','U.id = CL.user_id')
            ->join('coupon C','C.id = CL.coupon_id')
            ->append(['channel_desc','invalid_time_desc','use_time_desc','status_desc','cancel_btn'])
            ->limit($this->limitOffset, $this->limitLength)
            ->where($where)
            ->field('CL.*,C.name,U.sn,U.nickname,U.avatar')
            ->order(['CL.id'=>'desc'])
            ->select();
        $adminIds = array_column($couponList->toArray(),'admin_id');
        $adminList = [];
        if($adminIds){
            $adminList = Admin::where(['id'=>$adminIds])->column('name','id');
        }
        //优惠券列表
        foreach ($couponList as $coupon) {
            $coupon->admin_name = $adminList[$coupon->admin_id] ?? '';
        }

        return $couponList->toArray();

    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        $where = $this->setSearch();

        return CouponList::alias('CL')->where($where)
        ->join('user U','U.id = CL.user_id')
        ->join('coupon C','C.id = CL.coupon_id')->count();

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
        if(isset($this->params['keyword']) && $this->params['keyword']){
            $where[] = ['U.sn|U.nickname','like','%'.$this->params['keyword'].'%'];
        }
        if(isset($this->params['name']) && $this->params['name']){
            $where[] = ['C.name','like','%'.$this->params['name'].'%'];
        }
        if(isset($this->params['get_type']) && $this->params['get_type']){
            $where[] = ['C.get_type','=',$this->params['get_type']];
        }
        if(isset($this->params['admin_id']) && $this->params['admin_id']){
            $where[] = ['CL.admin_id','=',$this->params['admin_id']];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['CL.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['CL.create_time','<=',strtotime($this->params['end_time'])];
        }
        if(isset($this->params['status']) && $this->params['status']){
            $where[] = ['CL.status','=',$this->params['status']];
        }
        return $where;

    }
}