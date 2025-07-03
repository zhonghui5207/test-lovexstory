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

namespace app\common\model\distribution;

use app\common\enum\DistributionEnum;
use app\common\model\BaseModel;
use app\common\service\FileService;
use think\model\concern\SoftDelete;


class DistributionLevel extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * @notes 等级级别
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/10/20 5:57 下午
     */
    public function getLevelDescAttr($value, $data)
    {
        $defaultStr = $data['is_default'] ? '级(默认等级)' : '级';
        return $data['level'] . $defaultStr;
    }

    /**
     * @notes 分销商数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/20 6:01 下午
     */
    public function getDistributorNumAttr($value, $data)
    {
        return Distribution::where(['is_distribution' => 1,'level_id'=>$data['id']])->count();
    }


    /**
     * @notes 等级图标获取器
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/10/23 10:38 上午
     */
    public function getLevelIcoAttr($value, $data)
    {
        return FileService::getFileUrl($value);
    }

    /**
     * @notes 等级图标设置器
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/10/23 10:39 上午
     */
    public function setLevelIcoAttr($value, $data)
    {
        return FileService::setFileUrl($value);
    }


    /**
     * @notes 等级背景图获取器
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/10/23 10:38 上午
     */
    public function getLevelBgAttr($value, $data)
    {
        return FileService::getFileUrl($value);
    }

    /**
     * @notes 等级背景图设置器
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/10/23 10:39 上午
     */
    public function setLevelBgAttr($value, $data)
    {
        return FileService::setFileUrl($value);
    }

    /**
     * @notes 升级条件
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/10/26 10:45 上午
     */
    public function getUpgradeConditionsDescAttr($value, $data)
    {
        return DistributionEnum::getUpgradeConditionsDesc($data['upgrade_conditions']);
    }
}