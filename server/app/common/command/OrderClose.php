<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------
namespace app\common\command;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\logic\OrderLogLogic;
use app\common\model\order\Order;
use app\common\model\order\OrderLog;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Exception;
use think\facade\Db;
use think\facade\Log;

/**
 * 关闭超时未支付的订单
 * Class OrderClose
 * @package app\common\command
 */
class OrderClose extends Command
{
    protected function configure()
    {
        $this->setName('order_close')
            ->setDescription('关闭超时未支付的订单');
    }


    protected function execute(Input $input, Output $output)
    {

        try {
            Db::startTrans();
            $now = time();

            $orderIds = Order::where(['order_status' => OrderEnum::ORDER_STSTUS_WAITPAY])
                ->where('cancel_time', '>', 0)
                ->where('cancel_time', '<=', $now)
                ->column('id');

            if($orderIds){
                //关闭订单
                Order::where(['id'=>$orderIds])
                    ->update(['order_status'=>OrderEnum::ORDER_STSTUS_CLONE,'cancel_time'=>$now]);
                $orderLogLogic = new OrderLogLogic();
                foreach ($orderIds as $id){
                    $orderLogLogic->record(OrderLogEnum::TYPE_SYSTEM,OrderLogEnum::SYSTEM_CANCEL_REMARKS,$id);
                }
            }
            Db::commit();

        }catch (Exception $e){
            Db::rollback();
            Log::write('关闭超时未支付的订单更新失败:'.json_encode($e->getMessage()));
        }

    }
}