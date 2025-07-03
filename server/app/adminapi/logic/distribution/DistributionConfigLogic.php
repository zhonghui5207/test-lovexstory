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

namespace app\adminapi\logic\distribution;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;

class DistributionConfigLogic extends BaseLogic
{
    /**
     * @notes 获取基础设置
     * @return array
     * @author ljj
     * @date 2023/10/20 4:01 下午
     */
    public static function getConfig()
    {
        $config = [
            'is_distribution' => ConfigService::get('distribution_config', 'is_distribution', config('project.distribution_config.is_distribution')),
            'distribution_level' => ConfigService::get('distribution_config', 'distribution_level', config('project.distribution_config.distribution_level')),
            'self_buy' => ConfigService::get('distribution_config', 'self_buy', config('project.distribution_config.self_buy')),
            'goods_detail' => ConfigService::get('distribution_config', 'goods_detail', config('project.distribution_config.goods_detail')),
            'goods_detail_user' => ConfigService::get('distribution_config', 'goods_detail_user', config('project.distribution_config.goods_detail_user')),
            'distribution_apply' => ConfigService::get('distribution_config', 'distribution_apply', config('project.distribution_config.distribution_apply')),
            'apply_image' => ConfigService::get('distribution_config', 'apply_image', config('project.distribution_config.apply_image')),
            'is_apply_protocol' => ConfigService::get('distribution_config', 'is_apply_protocol', config('project.distribution_config.is_apply_protocol')),
            'apply_protocol_content' => ConfigService::get('distribution_config', 'apply_protocol_content'),
        ];
        $config['apply_image'] = empty($config['apply_image']) ? FileService::getFileUrl(config('project.distribution_config.apply_image')) : FileService::getFileUrl($config['apply_image']);

        return $config;
    }

    /**
     * @notes 设置基础设置
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/10/20 4:22 下午
     */
    public static function setConfig($params)
    {
        ConfigService::set('distribution_config', 'is_distribution', $params['is_distribution']);
        ConfigService::set('distribution_config', 'distribution_level', $params['distribution_level']);
        ConfigService::set('distribution_config', 'self_buy', $params['self_buy']);
        ConfigService::set('distribution_config', 'goods_detail', $params['goods_detail']);
        ConfigService::set('distribution_config', 'goods_detail_user', $params['goods_detail_user']);
        ConfigService::set('distribution_config', 'distribution_apply', $params['distribution_apply']);
        ConfigService::set('distribution_config', 'apply_image', !empty($params['apply_image']) ? FileService::setFileUrl($params['apply_image']) : '');
        ConfigService::set('distribution_config', 'is_apply_protocol', $params['is_apply_protocol']);
        ConfigService::set('distribution_config', 'apply_protocol_content', $params['apply_protocol_content']);
        return true;
    }


    /**
     * @notes 获取结算设置
     * @return array
     * @author ljj
     * @date 2023/10/20 4:57 下午
     */
    public static function getSettleConfig()
    {
        $config = [
            'calculation_method' => ConfigService::get('distribution_config', 'calculation_method', config('project.distribution_config.calculation_method')),
            'settlement_time' => ConfigService::get('distribution_config', 'settlement_time', config('project.distribution_config.settlement_time')),
            'settlement_time_day' => ConfigService::get('distribution_config', 'settlement_time_day', config('project.distribution_config.settlement_time_day')),
        ];

        return $config;
    }

    /**
     * @notes 设置结算设置
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/10/20 5:01 下午
     */
    public static function setSettleConfig($params)
    {
        ConfigService::set('distribution_config', 'calculation_method', $params['calculation_method']);
        ConfigService::set('distribution_config', 'settlement_time', $params['settlement_time']);
        ConfigService::set('distribution_config', 'settlement_time_day', $params['settlement_time_day']);
        return true;
    }
}
