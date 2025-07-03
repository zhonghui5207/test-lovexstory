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
namespace app\adminapi\logic\marketing;
use app\common\enum\marketing\CouponEnum;
use app\common\enum\marketing\CouponListEnum;
use app\common\model\auth\Admin;
use app\common\model\course\Course;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponCourse;
use app\common\model\marketing\CouponList;
use think\Exception;
use think\facade\Db;

/**
 * 领券记录逻辑类
 * Class CouponLogic
 * @package app\adminapi\logic\marketing
 */
class CouponLitsLogic
{

    /**
     * @notes 其他列表
     * @return array
     * @author cjhao
     * @date 2023/4/13 15:24
     */
    public function otherLists()
    {
        return [
            'get_type' => CouponEnum::getGetTypeDesc(),
            'admin_list'    => Admin::column('name','id'),
        ];
    }

    /**
     * @notes 优惠券作废
     * @param $id
     * @return string
     * @author cjhao
     * @date 2023/4/13 15:32
     */
    public function cancel($id)
    {
        if(empty($id)){
            return '请选择优惠券';
        }
        $array = CouponList::where(['id'=>$id])->select()->toArray();
        foreach ($array as $val) {
            if ($val['status'] != CouponListEnum::STATUS_NOT_USE) {
                return '非未使用的优惠券，无法作废';
            }
        }

        CouponList::where(['id'=>$id])->update(['status'=>CouponListEnum::STATUS_CANCEL]);
        return true;

    }


}