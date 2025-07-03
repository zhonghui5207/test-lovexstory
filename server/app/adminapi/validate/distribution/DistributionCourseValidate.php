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


class DistributionCourseValidate extends BaseValidate
{
    protected $rule = [
        'id'  => 'require',
        'distribution_status' => 'require|in:0,1',
        'distribution_rule' => 'require|in:1,2',
        'rule' => 'requireIf:distribution_rule,2',
        'ids'  => 'require|array',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'distribution_status.require' => '分销状态不能为空',
        'distribution_status.in' => '分销状态值错误',
        'distribution_rule.require' => '佣金规则不能为空',
        'distribution_rule.in' => '佣金规则值错误',
        'rule.requireIf' => '佣金比例数据不存在',
        'ids.require' => '参数缺失',
        'ids.array' => '参数错误',
    ];

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneSet()
    {
        return $this->only(['id', 'distribution_status', 'distribution_rule', 'rule']);
    }

    public function sceneJoin()
    {
        return $this->only(['ids', 'distribution_status']);
    }
}