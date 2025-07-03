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
use app\common\lists\ListsExtendInterface;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionOrder;
use app\common\model\user\User;


class FansLists extends BaseAdminDataLists implements ListsExtendInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/24 4:14 下午
     */
    public function where(): array
    {
        $where = [];
        if(isset($this->params['user_info']) && $this->params['user_info']){
            $where[] = ['u.sn|u.nickname|u.mobile','like','%'.$this->params['user_info'].'%'];
        }
        if(isset($this->params['is_distribution']) && $this->params['is_distribution'] != ''){
            $user_ids = Distribution::where(['is_distribution'=>1])->column('user_id');
            if ($this->params['is_distribution'] == 0) {
                $where[] = ['u.id','notin',$user_ids];
            } else {
                $where[] = ['u.id','in',$user_ids];
            }
        }
        // 一级粉丝
        if (isset($this->params['level']) && $this->params['level'] == 1) {
            $where[] = ['u.first_leader', '=', $this->params['user_id']];
        }
        // 二级粉丝
        if (isset($this->params['level']) && $this->params['level'] == 2) {
            $where[] = ['u.second_leader', '=', $this->params['user_id']];
        }
        if (!isset($this->params['level']) || !in_array($this->params['level'],[1,2])) {
            $where[] = ['u.first_leader|u.second_leader', '=', $this->params['user_id']];
        }

        return $where;
    }

    /**
     * @notes 粉丝列表
     * @return array
     * @author ljj
     * @date 2023/10/24 4:31 下午
     */
    public function lists(): array
    {
        $lists = User::alias('u')
            ->leftJoin('distribution d', 'd.user_id = u.id')
            ->field('u.avatar,u.nickname,u.user_earnings,u.first_leader,d.is_distribution,d.distribution_time,d.is_freeze,u.create_time,d.user_id')
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            $item['total_earnings'] = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_SUCCESS,'user_id'=>$item['user_id']])->sum('earnings');;
            $item['first_leader_name'] = User::where('id',$item['first_leader'])->value('nickname');
            $item['is_distribution_desc'] = $item['is_distribution'] ? '已开通' : '未开通';
            $item['distribution_time'] = empty($item['distribution_time']) ? '' : date('Y-m-d H:i:s', $item['distribution_time']);
            $item['is_freeze_desc'] = $item['is_freeze'] ? '冻结' : '正常';
        }

        return $lists;
    }

    /**
     * @notes 粉丝数量
     * @return int
     * @author ljj
     * @date 2023/10/24 4:31 下午
     */
    public function count(): int
    {
        return User::alias('u')
            ->leftJoin('distribution d', 'd.user_id = u.id')
            ->where($this->where())
            ->count();
    }

    /**
     * @notes 数据统计
     * @return array
     * @author ljj
     * @date 2023/10/24 4:38 下午
     */
    public function extend()
    {
        $user = User::where('id',$this->params['user_id'])->findOrEmpty()->toArray();
        return [
            'name' => $user['nickname'].'('.$user['sn'].')',
            'fans' => User::where(['first_leader|second_leader'=>$this->params['user_id']])->count(),
            'fans_distribution' => User::alias('u')
                ->leftjoin('distribution d', 'd.user_id = u.id')
                ->where(['u.first_leader|u.second_leader'=>$this->params['user_id'],'d.is_distribution'=>1])
                ->count(),
            'fans_one' => User::where(['first_leader'=>$this->params['user_id']])->count(),
            'fans_one_distribution' => User::alias('u')
                ->leftjoin('distribution d', 'd.user_id = u.id')
                ->where(['u.first_leader'=>$this->params['user_id'],'d.is_distribution'=>1])
                ->count(),
            'fans_two' => User::where(['second_leader'=>$this->params['user_id']])->count(),
            'fans_two_distribution' => User::alias('u')
                ->leftjoin('distribution d', 'd.user_id = u.id')
                ->where(['u.second_leader'=>$this->params['user_id'],'d.is_distribution'=>1])
                ->count(),
        ];
    }
}