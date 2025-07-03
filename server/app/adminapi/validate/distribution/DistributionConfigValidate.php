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

namespace app\adminapi\validate\distribution;

use app\common\validate\BaseValidate;


class DistributionConfigValidate extends BaseValidate
{
    protected $rule = [
        'is_distribution' => 'require|in:0,1',
        'distribution_level'  => 'require|in:1,2',
        'self_buy'   => 'require|in:0,1',
        'goods_detail'   => 'require|in:0,1',
        'goods_detail_user' => 'require|in:1,2',
        'distribution_apply' => 'require|in:1,2,3',
        'is_apply_protocol' => 'require|in:0,1',
        'calculation_method' => 'require|in:1',
        'settlement_time' => 'require|in:1',
        'settlement_time_day' => 'require|float|gt:0',
    ];

    protected $message = [
        'is_distribution.require' => '请选择是否开启分销功能',
        'is_distribution.in' => '分销功能值错误',
        'distribution_level.require' => '请选择分销层级',
        'distribution_level.in' => '分销层级值错误',
        'self_buy.require' => '请选择是否开启自购返佣',
        'self_buy.in' => '自购返佣值错误',
        'goods_detail.require' => '请选择商品详情显示佣金状态',
        'goods_detail.in' => '商品详情显示佣金值错误',
        'goods_detail_user.require' => '请选择详情页佣金可见用户',
        'goods_detail_user.in' => '详情页佣金可见用户值错误',
        'distribution_apply.require' => '请选择开通分销条件',
        'distribution_apply.in' => '开通分销条件值错误',
        'is_apply_protocol.require' => '请选择是否显示申请协议',
        'is_apply_protocol.in' => '申请协议值错误',
        'calculation_method.require' => '请选择佣金计算方式',
        'calculation_method.in' => '佣金计算方式值错误',
        'settlement_time.require' => '请选择结算时机',
        'settlement_time.in' => '结算时机值错误',
        'settlement_time_day.require' => '请输入结算天数',
        'settlement_time_day.float' => '结算天数值错误',
        'settlement_time_day.gt' => '结算天数必须大于0',
    ];


    public function sceneSetConfig()
    {
        return $this->only(['is_distribution','distribution_level','self_buy','goods_detail','goods_detail_user','distribution_apply','is_apply_protocol']);
    }

    public function sceneSetSettleConfig()
    {
        return $this->only(['calculation_method','settlement_time','settlement_time_day']);
    }
}