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

namespace app\api\validate;

use app\common\enum\DistributionEnum;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionApply;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\validate\BaseValidate;


class DistributionValidate extends BaseValidate
{
    protected $rule = [
        'code'      => 'require|checkCode',
        'user_id'   => 'require',
        'real_name' => 'require',
        'mobile' => 'require|mobile',
        'province'  => 'require|integer',
        'city'      => 'require|integer',
        'district'  => 'require|integer',
        'reason'    => 'require',
    ];

    protected $message = [
        'code.require'      => '请输入邀请码',
        'real_name.require' => '请输入真实姓名',
        'mobile.require' => '请输入手机号码',
        'mobile.mobile' => '手机号码格式错误',
        'province.require'  => '请选择省份',
        'province.integer'  => '省份格式错误',
        'city.require'      => '请选择市',
        'city.integer'      => '市格式错误',
        'district.require'  => '请选择区',
        'district.integer'  => '区格式错误',
        'reason.require'    => '请输入申请原因',
    ];


    public function sceneCode()
    {
        return $this->only(['code']);
    }


    public function sceneApply()
    {
        return $this->only(['user_id','real_name','mobile','province','city','district','reason'])
            ->append('user_id','checkApply');
    }

    /**
     * @notes 校验邀请码
     * @param $code
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/10/26 11:54 上午
     */
    public function checkCode($code, $rule, $data)
    {
        // 获取分销配置
        $is_distribution = ConfigService::get('distribution_config', 'is_distribution', config('project.distribution_config.is_distribution'));
        if(!$is_distribution) {
            return '分销功能已关闭，无法绑定上下级';
        }
        $firstLeader = User::alias('u')
            ->leftJoin('distribution d', 'd.user_id = u.id')
            ->field('u.id,u.first_leader,u.second_leader')
            ->where('d.code', $code)
            ->findOrEmpty();

        if($firstLeader->isEmpty()) {
            return '无效的邀请码';
        }

        $user = User::findOrEmpty($data['user_id']);
        if($user->first_leader) {
            return '已有上级';
        }
        if($firstLeader->id == $data['user_id']) {
            return '上级不能是自己';
        }
        if(in_array($data['user_id'], [$firstLeader->first_leader,$firstLeader->second_leader])) {
            return '不允许填写自己任一下级的邀请码';
        }
        return true;
    }

    /**
     * @notes 校验分销申请
     * @param $user_id
     * @return bool|string
     * @author ljj
     * @date 2023/10/26 2:31 下午
     */
    public function checkApply($user_id)
    {
        $distribution_apply = ConfigService::get('distribution_config', 'distribution_apply', config('project.distribution_config.distribution_apply'));
        if (!in_array($distribution_apply,[1,2])) {
            return '未开通分销申请通道，无法申请';
        }

        $is_distribution = Distribution::where('user_id', $user_id)->value('is_distribution');
        if($is_distribution) {
            return '您已是分销会员，无需申请';
        }
        $distributionApply = DistributionApply::where('user_id', $user_id)->findOrEmpty();
        if(!$distributionApply->isEmpty() && $distributionApply->status == DistributionEnum::STATUS_WAIT) {
            return '审核中,请勿重复提交申请';
        }

        return true;
    }
}