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
namespace app\adminapi\controller\marketing;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\marketing\CouponListLists;
use app\adminapi\logic\marketing\CouponLitsLogic;
use app\common\model\marketing\CouponList;

/**
 * 领券记录控制器类
 * Class CouponListController
 * @package app\adminapi\controller\marketing
 */
class CouponListController extends BaseAdminController
{

    /**
     * @notes 其他列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/13 15:25
     */
    public function otherLists()
    {
        $otherLists = (new CouponLitsLogic())->otherLists();
        return $this->data($otherLists);
    }


    /**
     * @notes 领券记录列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/13 15:21
     */
    public function lists()
    {
        return $this->dataLists(new CouponListLists());
    }


    /**
     * @notes 优惠券作废
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/13 15:30
     */
    public function cancel()
    {
        $result = (new CouponLitsLogic())->cancel($this->request->post('id'));
        if(true === $result){
            return $this->success('作废成功');
        }
        return $this->fail($result);
    }


}