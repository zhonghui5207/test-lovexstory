<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\common\logic;


use app\common\enum\YesNoEnum;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionLevel;
use app\common\service\ConfigService;

class RegisterLogic extends BaseLogic
{
    /**
     * @notes 新注册用户分销处理
     * @param $user_id
     * @return bool
     * @author ljj
     * @date 2023/10/30 6:57 下午
     */
    public function distribution($user_id)
    {
        //分销功能：1-开启；0-关闭；
        $is_distribution = ConfigService::get('distribution_config', 'is_distribution', config('project.distribution_config.is_distribution'));
        //开通分销会员条件：1-无条件；2-申请分销；3-制定分销；
        $distribution_apply = ConfigService::get('distribution_config', 'distribution_apply', config('project.distribution_config.distribution_apply'));
        //自动成为分销商
        if ($is_distribution == 1 && $distribution_apply == 1) {
            $distribution = Distribution::where('user_id', $user_id)->findOrEmpty();
            $default_level = DistributionLevel::where(['is_default'=>1])->findOrEmpty()->toArray();
            if ($distribution->isEmpty()) {
                Distribution::create([
                    'user_id' => $user_id,
                    'level_id' => $default_level['id'],
                    'is_distribution' => YesNoEnum::YES,
                    'distribution_time' => time(),
                    'code' => createInvitationCode(),
                ]);
            } else {
                Distribution::where('user_id', $user_id)
                    ->update(['is_distribution' => YesNoEnum::YES, 'distribution_time' => time()]);
            }
        }
        return true;
    }
}