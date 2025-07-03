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
use app\common\enum\OrderRefundEnum;
use app\common\lists\ListsExtendInterface;
use app\common\model\order\OrderRefund;

class OrderRefundLists extends BaseAdminDataLists implements ListsExtendInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2022/9/9 4:31 下午
     */
    public function where()
    {
        $where = [];
        $params = $this->params;
        if (isset($params['refund_sn']) && $params['refund_sn'] != '') {
            $where[] = ['or.sn','like','%'.$params['refund_sn'].'%'];
        }
        if (isset($params['source_sn']) && $params['source_sn'] != '') {
            $where[] = ['o.sn','like','%'.$params['source_sn'].'%'];
        }
        if (isset($params['user_info']) && $params['user_info'] != '') {
            $where[] = ['u.sn|u.nickname','like','%'.$params['user_info'].'%'];
        }
        if (isset($params['refund_type']) && $params['refund_type'] != '') {
            $where[] = ['or.type','=',$params['refund_type']];
        }
        if (isset($params['start_time']) && $params['start_time'] != '') {
            $where[] = ['or.create_time','>=',strtotime($params['start_time'])];
        }
        if (isset($params['end_time']) && $params['end_time'] != '') {
            $where[] = ['or.create_time','<=',strtotime($params['end_time'])];
        }
        if (isset($params['refund_status']) && $params['refund_status'] != '') {
            switch ($params['refund_status']) {
                case 1:
                    $where[] = ['or.refund_status','=',0];
                    break;
                case 2:
                    $where[] = ['or.refund_status','=',1];
                    break;
                case 3:
                    $where[] = ['or.refund_status','=',2];
                    break;
            }
        }

        return $where;
    }

    /**
     * @notes 订单退款列表
     * @return array
     * @author ljj
     * @date 2022/9/9 4:37 下午
     */
    public function lists(): array
    {
        $where = self::where();

        $lists = (new OrderRefund())->alias('or')
            ->join('user u', 'u.id = or.user_id')
            ->join('order o', 'o.id = or.order_id')
            ->field('or.id,or.sn as refund_sn,or.user_id,or.type,or.refund_amount,or.refund_status,or.create_time,o.sn as source_sn')
            ->with(['user' => function($query){
                $query->field('id,sn,nickname,avatar,mobile');
            }])
            ->where($where)
            ->order(['or.id'=>'desc'])
            ->append(['type_desc','refund_status_desc'])
            ->limit($this->limitOffset, $this->limitLength)
            ->select();

        return $lists->toArray();
    }

    /**
     * @notes 订单退款数量
     * @return int
     * @author ljj
     * @date 2022/9/9 4:37 下午
     */
    public function count(): int
    {
        $where = self::where();

        return (new OrderRefund())->alias('or')
            ->join('user u', 'u.id = or.user_id')
            ->join('order o', 'o.id = or.order_id')
            ->where($where)
            ->count();
    }

    /**
     * @notes 订单退款数据统计
     * @return array
     * @author ljj
     * @date 2022/9/9 4:41 下午
     */
    public function extend(): array
    {
        $where = self::where();
        foreach ($where as $key=>$val) {
            if ($val[0] == 'or.refund_status') {
                unset($where[$key]);
            }
        }

        $lists = (new OrderRefund())->alias('or')
            ->join('user u', 'u.id = or.user_id')
            ->join('order o', 'o.id = or.order_id')
            ->field('or.refund_status,or.refund_amount')
            ->where($where)
            ->select()
            ->toArray();

        $all_count = 0;
        $refund_wait_count = 0;
        $refund_success_count = 0;
        $refund_fail_count = 0;
        $total_refund_amount = OrderRefund::sum('refund_amount');
        $refund_ing_amount = OrderRefund::where(['refund_status'=>OrderRefundEnum::STATUS_ING])->sum('refund_amount');
        $refund_success_amount = OrderRefund::where(['refund_status'=>OrderRefundEnum::STATUS_SUCCESS])->sum('refund_amount');
        $refund_fail_amount = OrderRefund::where(['refund_status'=>OrderRefundEnum::STATUS_FAIL])->sum('refund_amount');
        foreach ($lists as $val) {
            $all_count += 1;

            if ($val['refund_status'] == OrderRefundEnum::STATUS_ING) {
                $refund_wait_count += 1;
            }
            if ($val['refund_status'] == OrderRefundEnum::STATUS_SUCCESS) {
                $refund_success_count += 1;
            }
            if ($val['refund_status'] == OrderRefundEnum::STATUS_FAIL) {
                $refund_fail_count += 1;
            }
        }

        return [
            'all_count' => $all_count,
            'refund_wait_count' => $refund_wait_count,
            'refund_success_count' => $refund_success_count,
            'refund_fail_count' => $refund_fail_count,
            'total_refund_amount' => $total_refund_amount,
            'refund_ing_amount' => $refund_ing_amount,
            'refund_success_amount' => $refund_success_amount,
            'refund_fail_amount' => $refund_fail_amount,
        ];
    }
}