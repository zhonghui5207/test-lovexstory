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

namespace app\api\lists;


use app\common\enum\DistributionEnum;
use app\common\model\distribution\DistributionOrder;
use app\common\service\FileService;

class DistributionOrderLists extends BaseApiDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/26 5:07 下午
     */
    public function where()
    {
        $where[] = ['do.user_id','=',$this->userId];
        if (isset($this->params['keyword']) && $this->params['keyword'] != '') {
            $where[] = ['c.name|o.sn|u.nickname','like','%'.$this->params['keyword'].'%'];
        }
        if (isset($this->params['status']) && $this->params['status'] != '') {
            $where[] = ['do.status','=',$this->params['status']];
        }

        return $where;
    }

    /**
     * @notes 分销订单列表
     * @return array
     * @author ljj
     * @date 2023/10/26 5:20 下午
     */
    public function lists(): array
    {
        $lists = DistributionOrder::alias('do')
            ->join('user u', 'u.id = do.user_id')
            ->join('order_course oc', 'oc.id = do.order_course_id')
            ->join('order o', 'o.id = oc.order_id')
            ->join('course c', 'c.id = do.course_id')
            ->field('do.id,do.earnings,do.status,do.create_time,u.nickname,u.avatar,o.sn as order_sn,o.order_amount,c.name as course_name,c.cover as course_cover,c.sell_price')
            ->where($this->where())
            ->order('do.id','desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$list) {
            $list['course_num'] = 1;
            $list['status_desc'] = DistributionEnum::getCommissionStatusDesc($list['status']);
            $list['avatar'] = !empty($list['avatar']) ? FileService::getFileUrl($list['avatar']) : '';
            $list['course_cover'] = !empty($list['avatar']) ? FileService::getFileUrl($list['course_cover']) : '';
        }

        return $lists;
    }

    /**
     * @notes 分销订单数量
     * @return int
     * @author ljj
     * @date 2023/10/26 5:19 下午
     */
    public function count(): int
    {
        return DistributionOrder::alias('do')
            ->join('user u', 'u.id = do.user_id')
            ->join('order_course oc', 'oc.id = do.order_course_id')
            ->join('order o', 'o.id = oc.order_id')
            ->join('course c', 'c.id = do.course_id')
            ->where($this->where())
            ->count();
    }
}