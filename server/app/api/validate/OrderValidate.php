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
namespace app\api\validate;

use app\common\enum\OrderEnum;
use app\common\model\order\Order;
use app\common\validate\BaseValidate;

/**
 * 订单验证器
 * Class OrderValidate
 * @package app\api\validate
 */
class OrderValidate extends BaseValidate
{

    protected $rule = [
        'id'        => 'require|checkOrder',
        'course_id' => 'require',
    ];

    protected $message = [
        'id.require'            => '请选择订单',
        'course_id.require'     => '请选择课程',
    ];

    protected function sceneSumbit()
    {
        return $this->only(['course_id']);
    }

    protected function sceneId()
    {
        return $this->only(['id']);
    }

    protected function checkOrder($value, $rule, $data)
    {
        $order = Order::where(['user_id' => $data['user_id'], 'id' => $data['id']])
                ->findOrEmpty();
        if($order->isEmpty()){
            return '订单不存在';
        }
        if(isset($data['type']) && 'del' == $data['type'] && OrderEnum::ORDER_STSTUS_CLONE != $order->order_status){
            return '订单不允许删除';
        }
        return true;
    }
}