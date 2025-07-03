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
namespace app\api\lists;
use app\common\model\order\Order;

/**
 * 订单列表
 * Class OrderLists
 * @package app\api\lists
 */
class OrderLists extends BaseApiDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = Order::alias('O')
            ->join('order_course OC','O.id = OC.order_id')
            ->where($this->setSearch())
            ->with('order_course')
            ->append(['order_status_desc','pay_btn','cancel_btn','comment_btn','del_btn'])
            ->order('O.id desc')
            ->field('O.id,O.sn,O.order_status,O.pay_status,O.pay_way,O.cancel_time,O.coupon_list_id,O.order_amount')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()->toarray();
        return $lists;

    }



    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {

        $count = Order::alias('O')
            ->join('order_course OC','O.id = OC.order_id')
            ->where($this->setSearch())
            ->count();
        return $count;

    }

    /**
     * @notes 设置搜索
     * @return array
     * @author cjhao
     * @date 2022/6/15 16:46
     */
    public function setSearch()
    {
        $where[] = ['O.user_id','=',$this->userId];
        $this->params['type'] && $where[] = ['order_status','=',$this->params['type']];
        return $where;
    }


}
