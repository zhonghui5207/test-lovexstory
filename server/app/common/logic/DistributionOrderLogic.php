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

namespace app\common\logic;

use app\common\enum\DistributionEnum;
use app\common\model\course\Course;
use app\common\model\distribution\DistributionCourse;
use app\common\model\distribution\DistributionLevel;
use app\common\model\distribution\DistributionOrder;
use app\common\model\order\Order;
use app\common\model\user\User;
use app\common\service\ConfigService;

class DistributionOrderLogic extends BaseLogic
{
    /**
     * @notes 添加分佣订单
     * @param $order_id
     * @return bool
     * @author ljj
     * @date 2023/10/30 9:47 上午
     */
    public static function add($order_id)
    {
        // 获取分销配置
        $is_distribution = ConfigService::get('distribution_config', 'is_distribution', config('project.distribution_config.is_distribution'));
        if(!$is_distribution) {
            return false;
        }

        //订单信息
        $order = Order::with(['order_course'])->where(['id'=>$order_id])->findOrEmpty()->toArray();

        //分销层级
        $distribution_level = ConfigService::get('distribution_config', 'distribution_level', config('project.distribution_config.distribution_level'));
        //自购返佣
        $self_buy = ConfigService::get('distribution_config', 'self_buy', config('project.distribution_config.self_buy'));

        // 用户信息
        $userInfo = User::alias('u')
            ->leftJoin('distribution d', 'd.user_id = u.id')
            ->field('u.id,u.first_leader,u.second_leader,d.level_id,d.is_distribution,d.is_freeze')
            ->where('u.id', $order['user_id'])
            ->findOrEmpty()
            ->toArray();

        // 判断商品是否参与分销
        $course = Course::field('id,distribution_status,distribution_rule')->where(['id'=>$order['order_course'][0]['course_id']])->findOrEmpty()->toArray();
        if(!$course['distribution_status']) {
            return false;
        }

        // 分销层级
        switch($distribution_level)
        {
            case 1: // 一级分销
                self::firstLevelCommission($userInfo, $order, $course['distribution_rule']);
                break;
            case 2: // 一级、二级分销
                self::firstLevelCommission($userInfo, $order, $course['distribution_rule']);
                self::secondLevelCommission($userInfo, $order, $course['distribution_rule']);
                break;
        }

        // 自购返佣
        if($self_buy && $userInfo['is_distribution'] && !$userInfo['is_freeze'])  {
            self::selfPurchaseCommission($userInfo, $order, $course['distribution_rule']);
        }

        return true;
    }

    /**
     * @notes 一级分佣
     * @param $userInfo
     * @param $item
     * @param $distributionRule
     * @return false|void
     * @author ljj
     * @date 2023/10/27 6:50 下午
     */
    public static function firstLevelCommission($userInfo, $item, $distributionRule)
    {
        if(!$userInfo['first_leader']) {
            // 没有上级，无需分佣
            return false;
        }
        $firstLeaderInfo = User::alias('u')
            ->leftJoin('distribution d', 'd.user_id = u.id')
            ->field('u.id,u.first_leader,u.second_leader,d.level_id,d.is_distribution,d.is_freeze')
            ->where('u.id', $userInfo['first_leader'])
            ->findOrEmpty()
            ->toArray();
        if(!$firstLeaderInfo['is_distribution'] || $firstLeaderInfo['is_freeze']) {
            // 上级不是分销会员 或 分销资格已冻结
            return false;
        }

        $ratioArr = self::getRatio($distributionRule, $item, $firstLeaderInfo);
        $firstLeaderInfo['ratio'] = $ratioArr['first_ratio'] ?? 0;
        $firstLeaderInfo['level'] = DistributionEnum::COMMISSION_LEVEL_ONE;
        self::addDistributionOrderGoods($item, $firstLeaderInfo);
    }

    /**
     * @notes 二级分佣
     * @param $userInfo
     * @param $item
     * @param $distributionRule
     * @return false|void
     * @author ljj
     * @date 2023/10/30 9:44 上午
     */
    public static function secondLevelCommission($userInfo, $item, $distributionRule)
    {
        if(!$userInfo['second_leader']) {
            // 没有上上级，无需分佣
            return false;
        }
        $secondLeaderInfo = User::alias('u')
            ->leftJoin('distribution d', 'd.user_id = u.id')
            ->field('u.id,u.first_leader,u.second_leader,d.level_id,d.is_distribution,d.is_freeze')
            ->where('u.id', $userInfo['second_leader'])
            ->findOrEmpty()
            ->toArray();
        if(!$secondLeaderInfo['is_distribution'] || $secondLeaderInfo['is_freeze']) {
            // 上上级不是分销会员 或 分销资格已冻结
            return false;
        }

        $ratioArr = self::getRatio($distributionRule, $item, $secondLeaderInfo);
        $secondLeaderInfo['ratio'] = $ratioArr['second_ratio'] ?? 0;
        $secondLeaderInfo['level'] = DistributionEnum::COMMISSION_LEVEL_TWO;
        self::addDistributionOrderGoods($item, $secondLeaderInfo);
    }

    /**
     * @notes 自购分佣
     * @param $userInfo
     * @param $item
     * @param $distributionRule
     * @author ljj
     * @date 2023/10/30 9:46 上午
     */
    public static function selfPurchaseCommission($userInfo, $item, $distributionRule)
    {
        $ratioArr = self::getRatio($distributionRule, $item, $userInfo);
        $userInfo['ratio'] = $ratioArr['self_ratio'] ?? 0;
        $userInfo['level'] = DistributionEnum::COMMISSION_LEVEL_SELF;
        self::addDistributionOrderGoods($item, $userInfo);
    }

    /**
     * @notes 获取佣金比例
     * @param $distributionRule
     * @param $item
     * @param $userInfo
     * @return array|void
     * @author ljj
     * @date 2023/10/27 7:00 下午
     */
    public static function getRatio($distributionRule, $item, $userInfo)
    {
        // 按分销会员等级对应的比例
        if($distributionRule == 1) {
            $ratioArr = DistributionLevel::field('first_ratio,second_ratio,self_ratio')
                ->where('id', $userInfo['level_id'])
                ->findOrEmpty()
                ->toArray();
            return $ratioArr;
        }

        // 单独设置的比例
        if($distributionRule == 2) {
            $ratioArr = DistributionCourse::field('first_ratio,second_ratio,self_ratio')
                ->where([
                    'course_id' => $item['order_course'][0]['course_id'],
                    'level_id' => $userInfo['level_id']
                ])
                ->order('id','desc')
                ->findOrEmpty()
                ->toArray();
            return $ratioArr;
        }
    }

    /**
     * @notes 生成分销订单
     * @param $item
     * @param $userInfo
     * @return false|void
     * @author ljj
     * @date 2023/10/30 9:36 上午
     */
    public static function addDistributionOrderGoods($item, $userInfo)
    {
        $earnings = 0;
        $calculation_method = ConfigService::get('distribution_config', 'calculation_method', config('project.distribution_config.calculation_method'));
        if($calculation_method == 1) {//佣金计算方式：1-商品实际支付金额
            $earnings = round(($item['order_amount'] * $userInfo['ratio'] / 100), 2);
        }
        if($earnings <= 0) {
            return false;
        }

        DistributionOrder::create([
            'user_id' => $userInfo['id'],
            'order_course_id' => $item['order_course'][0]['id'],
            'course_id' => $item['order_course'][0]['course_id'],
            'level_id' => $userInfo['level_id'],
            'commission_level' => $userInfo['level'],
            'ratio' => $userInfo['ratio'],
            'earnings' => $earnings,
        ]);
    }
}