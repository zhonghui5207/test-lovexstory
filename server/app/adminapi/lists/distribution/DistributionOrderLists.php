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

namespace app\adminapi\lists\distribution;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\DistributionEnum;
use app\common\model\distribution\DistributionOrder;
use app\common\service\FileService;


class DistributionOrderLists extends BaseAdminDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/25 11:37 上午
     */
    public function where(): array
    {
        $where = [];
        if (isset($this->params['order_sn']) && !empty($this->params['order_sn']))
        {
            $where[] = ['o.sn', 'like', '%' . $this->params['order_sn'] . '%'];
        }
        if (isset($this->params['course_info']) && !empty($this->params['course_info']))
        {
            $where[] = ['c.name', 'like', '%' . $this->params['course_info'] . '%'];
        }
        if (isset($this->params['user_info']) && !empty($this->params['user_info']))
        {
            $where[] = ['u.sn|u.nickname|u.mobile', 'like', '%' . $this->params['user_info'] . '%'];
        }
        if (isset($this->params['distributor_info']) && !empty($this->params['distributor_info']))
        {
            $where[] = ['d.sn|d.nickname|d.mobile', 'like', '%' . $this->params['distributor_info'] . '%'];
        }
        if (isset($this->params['status']) && !empty($this->params['status']))
        {
            $where[] = ['do.status', '=',$this->params['status']];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['o.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['o.create_time','<=',strtotime($this->params['end_time'])];
        }

        return $where;
    }

    /**
     * @notes 分销订单列表
     * @return array
     * @author ljj
     * @date 2023/10/25 12:11 下午
     */
    public function lists(): array
    {
        $lists = DistributionOrder::alias('do')
            ->leftJoin('order_course oc', 'oc.id = do.order_course_id')
            ->leftJoin('order o', 'o.id = oc.order_id')
            ->leftJoin('course c', 'c.id = do.course_id')
            ->leftJoin('user d', 'd.id = do.user_id')
            ->leftJoin('user u','u.id = o.user_id')
            ->field('do.id,u.avatar as user_avatar,u.nickname as user_nickname,u.account as user_account,o.sn as order_sn,c.cover as course_cover,c.name as course_name,c.sell_price,o.order_amount,do.commission_level,d.avatar as distributor_avatar,d.nickname as distributor_nickname,d.account as distributor_account,d.id as distributor_id,do.ratio,do.earnings,do.status,do.settlement_time,o.create_time')
            ->where($this->where())
            ->order(['id'=>'desc'])
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['user_avatar'] = FileService::getFileUrl($item['user_avatar']);
            $item['course_cover'] = FileService::getFileUrl($item['course_cover']);
            $item['course_num'] = 1;
            $item['commission_level'] = DistributionEnum::getCommissionLevelDesc($item['commission_level']);
            $item['distributor_avatar'] = FileService::getFileUrl($item['distributor_avatar']);
            $item['status_desc'] = DistributionEnum::getCommissionStatusDesc($item['status']);
        }

        return $lists;
    }

    /**
     * @notes 分销订单数量
     * @return int
     * @author ljj
     * @date 2023/10/25 12:11 下午
     */
    public function count(): int
    {
        return DistributionOrder::alias('do')
            ->leftJoin('order_course oc', 'oc.id = do.order_course_id')
            ->leftJoin('order o', 'o.id = oc.order_id')
            ->leftJoin('course c', 'c.id = do.course_id')
            ->leftJoin('user d', 'd.id = do.user_id')
            ->leftJoin('user u','u.id = o.user_id')
            ->where($this->where())
            ->count();
    }
}