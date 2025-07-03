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

namespace app\common\command;


use app\common\enum\DistributionEnum;
use app\common\enum\PayEnum;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionLevel;
use app\common\model\distribution\DistributionOrder;
use app\common\model\order\Order;
use app\common\model\user\User;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;

class UpdateDistributionLevel extends Command
{
    protected function configure()
    {
        $this->setName('update_distribution_level')
            ->setDescription('更新分销等级');
    }

    protected function execute(Input $input, Output $output)
    {
        $lists = User::alias('u')
            ->Join('distribution d', 'd.user_id = u.id')
            ->Join('distribution_level dl', 'dl.id = d.level_id')
            ->field('d.user_id,dl.level')
            ->where([
                ['d.is_distribution', '=', 1],
            ])
            ->select()
            ->toArray();

        // 非默认等级
        $levels = DistributionLevel::where('is_default', 0)
            ->order('level', 'desc')
            ->field('id,name,level,upgrade_conditions,single_amount,total_amount,consume_num,settled_commission')
            ->select()
            ->toArray();

        if (empty($lists) || empty($levels)) {
            return false;
        }

        foreach ($lists as $item) {
            // 更新分销商等级
            foreach($levels as $level) {
                try{
                    $flag = false;
                    if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ANY) {
                        $flag = self::singleAmountFlag($item['user_id'],$level)
                            || self::totalAmountFlag($item['user_id'],$level)
                            || self::consumeNumFlag($item['user_id'],$level)
                            || self::returnedCommissionFlag($item['user_id'],$level);
                    }

                    // 全部条件满足升级
                    if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ALL) {
                        $flag = self::singleAmountFlag($item['user_id'],$level)
                            && self::totalAmountFlag($item['user_id'],$level)
                            && self::consumeNumFlag($item['user_id'],$level)
                            && self::returnedCommissionFlag($item['user_id'],$level);
                    }
                    if($flag && $level['level'] > $item['level']) {
                        // 满足升级条件且是升更高的等级
                        Distribution::where(['user_id' => $item['user_id']])->update(['level_id' => $level['id']]);
                    }
                }catch(\Exception $e) {
                    Log::write('更新分销等级出错:'.$e->__toString());
                }
            }
        }
        return true;
    }

    /**
     * @notes 判断是否满足单笔消费金额条件
     * @param $userId
     * @param $level
     * @return bool
     * @author ljj
     * @date 2023/10/30 3:41 下午
     */
    public static function singleAmountFlag($userId, $level)
    {
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ANY && $level['single_amount'] === null) {
            return false;
        }
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ALL && $level['single_amount'] === null) {
            return true;
        }

        $order = Order::where(['user_id'=> $userId,'pay_status'=>PayEnum::ISPAID])->order('order_amount', 'desc')->findOrEmpty();
        if($order->isEmpty()) {
            return false;
        }
        if($order->order_amount >= $level['single_amount']) {
            return true;
        }
        return false;
    }

    /**
     * @notes 判断是否满足累计消费金额条件
     * @param $userId
     * @param $level
     * @return bool
     * @author ljj
     * @date 2023/10/30 3:43 下午
     */
    public static function totalAmountFlag($userId, $level)
    {
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ANY && $level['total_amount'] === null) {
            return false;
        }
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ALL && $level['total_amount'] === null) {
            return true;
        }

        $total_amount = Order::where(['user_id' =>$userId,'pay_status'=>PayEnum::ISPAID])->sum('order_amount');
        if($total_amount >= $level['total_amount']) {
            return true;
        }
        return false;
    }

    /**
     * @notes 判断是否满足累计消费次数条件
     * @param $userId
     * @param $level
     * @return bool
     * @author ljj
     * @date 2023/10/30 3:44 下午
     */
    public static function consumeNumFlag($userId, $level)
    {
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ANY && $level['consume_num'] === null) {
            return false;
        }
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ALL && $level['consume_num'] === null) {
            return true;
        }

        $order_num = Order::where(['user_id'=> $userId,'pay_status'=>PayEnum::ISPAID])->count();
        if($order_num >= $level['consume_num']) {
            return true;
        }
        return false;
    }

    /**
     * @notes 判断是否消费已返佣金条件
     * @param $userId
     * @param $level
     * @return bool
     * @author ljj
     * @date 2023/10/30 3:46 下午
     */
    public static function returnedCommissionFlag($userId, $level)
    {
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ANY && $level['settled_commission'] === null) {
            return false;
        }
        if($level['upgrade_conditions'] == DistributionEnum::UPGRADE_CONDITIONS_ALL && $level['settled_commission'] === null) {
            return true;
        }

        $earnings = DistributionOrder::where(['user_id' =>$userId,'status'=>DistributionEnum::COMMISSION_STATUS_SUCCESS])->sum('earnings');
        if($earnings >= $level['settled_commission']) {
            return true;
        }
        return false;
    }
}