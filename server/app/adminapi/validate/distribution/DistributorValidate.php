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


class DistributorValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'user_ids' => 'require|array',
        'level_id' => 'require',
        'type' => 'require|in:1,2',
        'first_leader' => 'requireIf:type,1',
        'user_id' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'user_ids.require' => '请选择用户',
        'user_ids.array' => '用户信息错误',
        'level_id.require' => '请选择分销等级',
        'type.require' => '请选择调整方式',
        'type.in' => '调整方式值错误',
        'first_leader.requireIf' => '请选择上级分销商',
        'user_id.require' => '参数缺失',
    ];


    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneAdd()
    {
        return $this->only(['user_ids','level_id']);
    }

    public function sceneFreeze()
    {
        return $this->only(['id']);
    }

    public function sceneAdjustLevel()
    {
        return $this->only(['id','level_id']);
    }

    public function sceneAdjustFirstLeader()
    {
        return $this->only(['user_id','type','first_leader']);
    }
}