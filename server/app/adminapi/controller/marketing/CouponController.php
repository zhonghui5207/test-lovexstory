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
use app\adminapi\lists\marketing\CouponLists;
use app\adminapi\logic\marketing\CouponLogic;
use app\adminapi\validate\marketing\CouponValidate;

/**
 * 优惠券控制器类
 * Class CouponController
 * @package app\adminapi\cotroller\marketing
 */
class CouponController extends BaseAdminController
{


    /**
     * @notes 其他列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/13 15:25
     */
    public function otherLists()
    {
        $otherLists = (new CouponLogic())->otherLists();
        return $this->data($otherLists);
    }
    /**
     * @notes 优惠券列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/10 18:53
     */
    public function lists()
    {
        return $this->dataLists(new CouponLists());
    }


    /**
     * @notes 添加优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/11 18:18
     */
    public function add()
    {
        $params = (new CouponValidate())->post()->goCheck('add');
        $result = (new CouponLogic())->add($params);
        if(true === $result){
            return $this->success('添加成功');
        }
        return $this->fail($result);
    }


    /**
     * @notes 编辑优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/11 18:44
     */
    public function edit()
    {
        $params = (new CouponValidate())->post()->goCheck('edit');
        $result = (new CouponLogic())->edit($params);
        if(true === $result){
            return $this->success('修改成功');
        }
        return $this->fail($result);
    }


    /**
     * @notes 获取优惠券详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/11 19:00
     */
    public function detail()
    {
        $params = (new CouponValidate())->goCheck('id');
        $detail = (new CouponLogic())->detail($params['id']);
        return $this->success('',$detail);
    }


    /**
     * @notes 修改优惠券状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/12 15:19
     */
    public function status()
    {
        $params = (new CouponValidate())->post()->goCheck('id');
        $result = (new CouponLogic())->status($params['id']);
        if(true === $result){
            return $this->success('状态更新成功');
        }
        return $this->success($result);

    }

    /**
     * @notes 删除优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2023/4/12 18:41
     */
    public function del()
    {
        $params = (new CouponValidate())->post()->goCheck('id');
        $result = (new CouponLogic())->del($params['id']);
        if(true === $result){
            return $this->success('删除成功');
        }
        return $this->success($result);

    }

    /**
     * @notes 发放优惠券
     * @return \think\response\Json
     * @throws \Exception
     * @author ljj
     * @date 2023/8/28 11:34 上午
     */
    public function send()
    {
        $params = (new CouponValidate())->post()->goCheck('send',['admin_id'=>$this->adminId]);
        $result = CouponLogic::send($params);
        if ($result === true) {
            return $this->success('发放成功', [], 1, 1);
        }
        return $this->fail($result);
    }

}
