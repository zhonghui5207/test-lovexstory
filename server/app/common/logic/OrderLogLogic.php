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

namespace app\common\logic;


use app\common\enum\OrderLogEnum;
use app\common\model\order\OrderLog;

class OrderLogLogic extends BaseLogic
{
    /**
     * @notes 订单日志
     * @param int $type 类型:1-系统;2-后台;3-用户;
     * @param int $channel 渠道编号。变动方式
     * @param int $order_id 订单id
     * @param int $operator_id 操作人id
     * @param string $content 日志内容
     * @author ljj
     * @date 2022/2/11 3:37 下午
     */
    public function record(int $type,int $channel,int $order_id,int $operator_id = 0,string $content = '')
    {
        OrderLog::create([
            'type'          => $type,
            'channel'       => $channel,
            'order_id'      => $order_id,
            'operator_id'   => $operator_id,
            'content'       => $content ?: OrderLogEnum::getRecordDesc($channel),
            'create_time'   => time(),
            'update_time'   => time(),
        ]);
    }
}