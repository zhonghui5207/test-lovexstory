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
namespace app\api\controller;
use app\api\lists\CouponCourseLists;
use app\api\lists\CouponLists;
use app\api\lists\CouponUserLists;
use app\api\logic\CouponLogic;
use app\api\validate\CouponValidate;

/**
 * 优惠券控制器类
 * Class CouponController
 * @package app\api\controller
 */
class CouponController extends BaseApiController
{

    /**
     * @notes 优惠券列表
     * @author cjhao
     * @date 2023/5/4 10:53
     */
    public function lists()
    {
        return $this->dataLists(new CouponLists());
    }

    /**
     * @notes 领取优惠券
     * @return \think\response\Json
     * @author ljj
     * @date 2023/8/22 6:47 下午
     */
    public function receive()
    {
        $params = (new CouponValidate())->post()->goCheck('receive',['user_id'=>$this->userId]);
        (new CouponLogic())->receive($params);
        return $this->success('领取成功');
    }

    /**
     * @notes 用户优惠券列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/2/23 11:29 上午
     */
    public function userCouponLists()
    {
        return $this->dataLists(new CouponUserLists());
    }

    /**
     * @notes 订单结算页优惠券列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/2/24 6:42 下午
     */
    public function orderCoupon()
    {
        $params = (new CouponValidate())->get()->goCheck('orderCoupon',['user_id'=>$this->userId]);
        $result = (new CouponLogic())->orderCoupon($params);
        return $this->success('', $result);
    }


    /**
     * @notes 课程详情优惠券
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/8/28 9:47 上午
     */
    public function courseCoupon()
    {
        $params = (new CouponValidate())->get()->goCheck('courseCoupon',['user_id'=>$this->userId]);
        $result = (new CouponLogic())->courseCoupon($params);
        return $this->success('', $result);
    }


    /**
     * @notes 优惠券课程列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/8/28 10:20 上午
     */
    public function couponCourseLists()
    {
        return $this->dataLists(new CouponCourseLists());
    }
}