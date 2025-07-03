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

namespace app\common\command;

use app\common\enum\AccountLogEnum;
use app\common\enum\DistributionEnum;
use app\common\enum\OrderEnum;
use app\common\logic\AccountLogLogic;
use app\common\model\distribution\DistributionOrder;
use app\common\model\user\User;
use app\common\service\ConfigService;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\facade\Log;

class DistributionSettlement extends Command
{
    protected function configure()
    {
        $this->setName('distribution_settlement')
            ->setDescription('结算分销订单');
    }

    protected function execute(Input $input, Output $output)
    {
        //结算时机：1-订单完成后；
        $settlement_time = ConfigService::get('distribution_config', 'settlement_time', config('project.distribution_config.settlement_time'));
        //结算天数；
        $settlement_time_day = ConfigService::get('distribution_config', 'settlement_time_day', config('project.distribution_config.settlement_time_day'));
        $time = time() - ($settlement_time_day * 24 * 60 * 60);

        Db::startTrans();
        try{
            $lists = DistributionOrder::alias('do')
                ->leftJoin('order_course dc', 'dc.id = do.order_course_id')
                ->leftJoin('order o', 'o.id = dc.order_id')
                ->field('do.id,do.user_id,do.order_course_id,do.earnings,dc.order_id,o.sn as order_sn')
                ->where([
                    ['do.status', '=', DistributionEnum::COMMISSION_STATUS_WAIT],
                    ['o.order_status', '=', OrderEnum::ORDER_STSTUS_COMPLETE],
                    ['o.pay_time', '<=', $time],
                ])
                ->select()
                ->toArray();

            foreach ($lists as $item) {
                // 增加用户收益
                $user = User::findOrEmpty($item['user_id']);
                if (! $user->isEmpty()) {
                    $user->user_earnings = is_null($user->user_earnings) ? 0 : $user->user_earnings;
                    $user->user_earnings = $user->user_earnings + $item['earnings'];
                    $user->save();
                }

                // 记录账户流水
                AccountLogLogic::record($item['user_id'],AccountLogEnum::EAR,AccountLogEnum::EAR_INC_SETTLEMENT,AccountLogEnum::INC,$item['earnings'],$item['order_sn'],'');

                // 更新分销订单状态
                DistributionOrder::update([
                    'status' => DistributionEnum::COMMISSION_STATUS_SUCCESS,
                    'settlement_time' => time()
                ],['id'=>$item['id']]);

                // 消息通知
//                event('Notice', [
//                    'scene_id' => NoticeEnum::EARNINGS_NOTICE,
//                    'params' => [
//                        'user_id' => $item['user_id'],
//                        'earnings' => $item['earnings']
//                    ]
//                ]);
            }

            Db::commit();
        }catch(\Exception $e) {
            Db::rollback();
            Log::write('结算分销订单出错:'.$e->__toString());
        }
    }
}