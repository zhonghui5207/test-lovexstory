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
namespace app\adminapi\controller\order;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\validate\order\OrderValidate;
use app\adminapi\logic\order\OrderLogic;

/**
 * 订单控制器类
 * Class OrderController
 * @package app\adminapi\controller\order
 */
class OrderController extends BaseAdminController
{

    /**
     * @notes 订单列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/15 14:35
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 其他订单
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/16 16:23
     */
    public function otherLists()
    {
        $otherLists = (new OrderLogic())->otherLists();
        return $this->success('',$otherLists);
    }

    /**
     * @notes 订单详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/16 11:59
     */
    public function detail()
    {
        $params = (new OrderValidate())->goCheck('id');
        $detail = (new OrderLogic())->detail($params['id']);
        return $this->success('',$detail);
    }

    /**
     * @notes 商家备注
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/16 12:07
     */
    public function shopRemark()
    {
        $params = (new OrderValidate())->post()->goCheck('remark');
        $params['admin_id'] = $this->adminId;
        (new OrderLogic())->shopRemark($params);
        return $this->success('设置成功');
    }

    /**
     * @notes 取消订单
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/16 14:45
     */
    public function cancel()
    {
        $params = (new OrderValidate())->post()->goCheck('id');
        $result = (new OrderLogic())->cancel($params['id'],$this->adminId);
        if(true === $result){
            return $this->success('取消成功');
        }
        return $this->fail($result);
    }

    /**
     * @notes 售后退款
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/22 15:16
     */
    public function refund()
    {
        $params = (new OrderValidate())->post()->goCheck('id');
        $params['admin_id'] = $this->adminId;
        $result = (new OrderLogic())->refund($params);
        if(true === $result){
            return $this->success('退款成功',[],1,1);
        }
        return $this->fail($result);

    }

}