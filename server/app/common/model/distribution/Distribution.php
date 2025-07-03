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
use think\model\concern\SoftDelete;

class Distribution extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


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
        if ($data['first_leader'] === null) {
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
     * @notes 成为分销商时间
     * @param $value
     * @return false|string
     * @author ljj
     * @date 2023/10/23 6:22 下午
     */
    public function getDistributionTimeAttr($value,$data)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '-';
    }

    /**
     * @notes 分销等级名称
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/10/23 6:24 下午
     */
    public function getLevelNameAttr($value,$data)
    {
        return DistributionLevel::where('id',$data['level_id'])->value('name');
    }

    /**
     * @notes 已入账佣金
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/23 6:25 下午
     */
    public function getEarningsAttr($value,$data)
    {
        return DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_SUCCESS,'user_id'=>$data['user_id']])->sum('earnings');
    }

    /**
     * @notes 待结算佣金
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/23 6:25 下午
     */
    public function getWaitEarningsAttr($value,$data)
    {
        return DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_WAIT,'user_id'=>$data['user_id']])->sum('earnings');
    }

    /**
     * @notes 分销状态
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/10/23 6:27 下午
     */
    public function getIsFreezeDescAttr($value,$data)
    {
        $result = ['正常','冻结'];
        return $result[$data['is_freeze']] ?? '';
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
     * @notes 分销订单数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/23 6:50 下午
     */
    public function getDistributionOrderNumAttr($value,$data)
    {
        return DistributionOrder::where(['user_id'=>$data['user_id']])->count();
    }

    /**
     * @notes 下级人数
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/23 6:54 下午
     */
    public function getFansAttr($value,$data)
    {
        return User::where(['first_leader|second_leader'=>$data['user_id']])->count();
    }

    /**
     * @notes 下级分销商人数
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/10/23 6:58 下午
     */
    public function getFansDistributionAttr($value,$data)
    {
        return User::alias('u')
            ->leftjoin('distribution d', 'd.user_id = u.id')
            ->where(['u.first_leader|u.second_leader'=>$data['user_id'],'d.is_distribution'=>1])
            ->count();
    }

    /**
     * @notes 下一级人数
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/23 6:58 下午
     */
    public function getFansOneAttr($value,$data)
    {
        return User::where(['first_leader'=>$data['user_id']])->count();
    }

    /**
     * @notes 下一级分销商人数
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/10/23 6:59 下午
     */
    public function getFansOneDistributionAttr($value,$data)
    {
        return User::alias('u')
            ->leftjoin('distribution d', 'd.user_id = u.id')
            ->where(['u.first_leader'=>$data['user_id'],'d.is_distribution'=>1])
            ->count();
    }

    /**
     * @notes 下二级人数
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/23 6:58 下午
     */
    public function getFansTwoAttr($value,$data)
    {
        return User::where(['second_leader'=>$data['user_id']])->count();
    }

    /**
     * @notes 下二级分销商人数
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/10/23 6:59 下午
     */
    public function getFansTwoDistributionAttr($value,$data)
    {
        return User::alias('u')
            ->leftjoin('distribution d', 'd.user_id = u.id')
            ->where(['u.second_leader'=>$data['user_id'],'d.is_distribution'=>1])
            ->count();
    }
}