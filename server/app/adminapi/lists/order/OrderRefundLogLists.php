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

namespace app\adminapi\lists\order;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\order\OrderRefundLog;

class OrderRefundLogLists extends BaseAdminDataLists
{
    /**
     * @notes 退款日志列表
     * @return array
     * @author ljj
     * @date 2022/9/9 5:52 下午
     */
    public function lists(): array
    {
        $lists = (new OrderRefundLog())->alias('orl')
            ->join('order_refund or', 'or.id = orl.refund_id')
            ->field('orl.id,orl.sn,or.refund_amount,orl.refund_status,orl.create_time,orl.operator_id,orl.type')
            ->where(['refund_id'=>$this->params['id']])
            ->order(['orl.id'=>'desc'])
            ->append(['operator_desc','refund_status_desc'])
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 退款日志数量
     * @return int
     * @author ljj
     * @date 2022/9/9 5:52 下午
     */
    public function count(): int
    {
        return (new OrderRefundLog())->alias('orl')
            ->join('order_refund or', 'or.id = orl.refund_id')
            ->where(['refund_id'=>$this->params['id']])
            ->count();
    }
}