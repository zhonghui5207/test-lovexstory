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

namespace app\adminapi\lists\recharge;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsExcelInterface;
use app\common\model\recharge\RechargeOrder;
use app\common\service\FileService;

/**
 * 充值记录列表
 * Class RecharLists
 * @package app\adminapi\lists
 */
class RechargeLists extends BaseAdminDataLists implements ListsExcelInterface
{
    /**
     * @notes 导出字段
     * @return array
     * @author Tab
     * @date 2021/9/22 18:20
     */
    public function setExcelFields(): array
    {
        return [
            'sn' => '充值单号',
            'nickname' => '用户昵称',
            'order_amount' => '充值金额',
            'give_money' => '赠送余额',
            'pay_way' => '支付方式',
            'pay_time' => '支付时间',
            'pay_status' => '订单状态',
            'create_time' => '下单时间',
        ];
    }

    /**
     * @notes 导出表名
     * @return string
     * @author Tab
     * @date 2021/9/22 18:20
     */
    public function setFileName(): string
    {
        return '充值记录';
    }



    /**
     * @notes 附加搜索条件
     * @author Tab
     * @date 2021/8/11 16:11
     */
    public function setSearch()
    {
        $where = [];
        // 用户编号
        if(isset($this->params['sn']) && !empty($this->params['sn'])) {
            $where[] = ['RO.sn', 'like', '%'.$this->params['sn'].'%'];
        }
        if(isset($this->params['keyword']) && $this->params['keyword']){
            $where[] = ['U.nickname|U.mobile','like','%'.$this->params['keyword'].'%'];
        }
        // 支付方式
        if(isset($this->params['pay_way']) && $this->params['pay_way']) {
            $where[]  = ['RO.pay_way', '=', $this->params['pay_way']];
        }
        // 支付状态
        if(isset($this->params['pay_status']) &&  '' != $this->params['pay_status'] ) {
            $where[] = ['RO.pay_status', '=', $this->params['pay_status'] ];
        }
        //下单时间
        if(isset($this->params['start_time']) &&  $this->params['start_time'] ) {
            $where[] = ['RO.create_time', '>', strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) &&  $this->params['end_time'] ) {
            $where[] = ['RO.create_time', '<', strtotime($this->params['end_time'])];
        }
        return $where;
    }

    /**
     * @notes 充值记录列表
     * @return array
     * @author Tab
     * @date 2021/8/11 16:30
     */
    public function lists(): array
    {
        // 附加搜索
        $field = 'RO.sn,RO.order_amount,RO.pay_way,RO.pay_time,RO.pay_status,RO.create_time,RO.award';
        $field .= ',U.avatar,U.nickname';
        $lists = RechargeOrder::alias('RO')
            ->leftJoin('user U', 'U.id = RO.user_id')
            ->field($field)
            ->where($this->setSearch())
            ->order('RO.id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            $item['avatar'] = FileService::getFileUrl($item['avatar']);
            $item['give_money'] = $this->giveMoney($item);
            $item['pay_time'] = empty($item['pay_time']) ? '' : date('Y-m-d H:i:s', $item['pay_time']);
            $item['total_money'] = $item['order_amount']+ $item['give_money'];
        }

        return $lists;
    }

    /**
     * @notes 充值记录数量
     * @return int
     * @author Tab
     * @date 2021/8/11 16:30
     */
    public function count(): int
    {


        $count = RechargeOrder::alias('RO')
            ->leftJoin('user U', 'U.id = RO.user_id')
            ->where($this->setSearch())
            ->count();

        return $count;
    }

    /**
     * @notes 充值赠送金额
     * @param $item
     * @return int|mixed|string
     * @author Tab
     * @date 2021/8/11 15:49
     */
    public function giveMoney($item)
    {
        if(!isset($item['award']) || empty($item['award'])) {
            return 0;
        }
        foreach($item['award'] as  $subItem) {
            if(isset($subItem['give_money'])) {
                return clear_zero($subItem['give_money']);
            }
        }
        return 0;
    }
}