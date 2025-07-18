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

use app\common\enum\PayEnum;
use app\common\validate\BaseValidate;

/**
 * 支付验证
 * Class PayValidate
 * @package app\api\validate
 */
class PayValidate extends BaseValidate
{
    protected $rule = [
        'from'      => 'require',
        'pay_way'   => 'require|in:' . PayEnum::BALANCE_PAY . ',' . PayEnum::WECHAT_PAY . ',' . PayEnum::ALI_PAY,
        'order_id'  => 'require'
    ];

    protected $message = [
        'from.require'      => '参数缺失',
        'pay_way.require'   => '支付方式参数缺失',
        'pay_way.in'        => '支付方式参数错误',
        'order_id.require'  => '订单参数缺失'
    ];

    /**
     * @notes 获取支付方式场景
     * @author Tab
     * @date 2021/8/28 10:24
     */
    public function scenePayway()
    {
        return $this->only(['from', 'order_id']);
//            ->append('scene','require');
    }

    public function scenePaystatus()
    {
        return $this->only(['from', 'order_id']);
    }

    public function sceneOrderAmount()
    {
        return $this->only(['from', 'order_id']);
    }

    /**
     * @notes 获取支付结果场景
     * @return PayValidate
     * @author 段誉
     * @date 2022/4/6 15:12
     */
    public function scenePayresult()
    {
        return $this->only(['from', 'order_id']);
    }

}