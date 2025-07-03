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
use app\common\model\user\User;
use app\common\service\FileService;

class DistributionApply extends BaseModel
{
    /**
     * @notes 申请状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/10/23 2:26 下午
     */
    public function getStatusDescAttr($value,$data)
    {
        return DistributionEnum::getStatusDesc($data['status']);
    }

    /**
     * @notes 用户头像
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/10/23 2:28 下午
     */
    public function getAvatarAttr($value,$data)
    {
        return FileService::getFileUrl($value);
    }

    /**
     * @notes 上级推荐人名称
     * @param $value
     * @param $data
     * @return mixed|string
     * @author ljj
     * @date 2023/10/23 2:36 下午
     */
    public function getFirstLeaderNameAttr($value,$data)
    {
        if ($data['first_leader'] == null) {
            return '-';
        }
        if ($data['first_leader'] == 0) {
            return '系统';
        }
        if ($data['first_leader'] > 0) {
            return User::where('id',$data['first_leader'])->value('nickname');
        }

        return '';
    }

    /**
     * @notes 审核时间
     * @param $value
     * @return false|string
     * @author ljj
     * @date 2023/10/23 5:22 下午
     */
    public function getAuditTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '-';
    }
}