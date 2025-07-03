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
namespace app\api\controller;

use app\api\logic\OrderLogic;
use app\api\validate\OrderValidate;

/**
 * 订单控制器类
 * Class OrderController
 * @package app\api\controller
 */
class OrderController extends BaseApiController
{

    /**
     * @notes 下单接口
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/9 11:04
     */
    public function sumbitOrder()
    {
        $params = (new OrderValidate())->post()->goCheck('sumbit');
        $result = (new OrderLogic())->sumbitOrder($params,$this->userInfo);
        if(is_array($result)){
            return $this->success('订单提交成功',$result);
        }
        return $this->fail($result);

    }

    /**
     * @notes 订单列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/15 16:32
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 订单详情
     * @author cjhao
     * @date 2022/6/15 18:11
     */
    public function detail()
    {
        $params = (new OrderValidate())->goCheck('id',['user_id'=>$this->userId]);
        $detail = (new OrderLogic())->detail($params);
        return $this->success('',$detail);
    }

    /**
     * @notes 取消订单
     * @author cjhao
     * @date 2022/6/15 18:44
     */
    public function cancel()
    {
        $params = (new OrderValidate())->post()->goCheck('id',['user_id'=>$this->userId]);
        $result = (new OrderLogic())->cancel($params);
        if(true === $result){
            return $this->success('取消成功');
        }
        return $this->fail($result);

    }

    /**
     * @notes 删除接口
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/10/24 16:55
     */
    public function del()
    {
        $params = (new OrderValidate())->post()->goCheck('id',['user_id'=>$this->userId,'type'=>'del']);
        $result = (new OrderLogic())->del($params);
        if(true === $result){
            return $this->success('删除成功');
        }
        return $this->fail($result);
    }


}