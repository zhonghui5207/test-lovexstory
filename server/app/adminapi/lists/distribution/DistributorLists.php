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
use app\common\lists\ListsExcelInterface;
use app\common\model\distribution\Distribution;

class DistributorLists extends BaseAdminDataLists implements ListsExcelInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/23 6:14 下午
     */
    public function where(): array
    {
        $where = [];
        if(isset($this->params['user_info']) && $this->params['user_info']){
            $where[] = ['u.sn|u.nickname|u.mobile','like','%'.$this->params['user_info'].'%'];
        }
        if(isset($this->params['level_id']) && $this->params['level_id']){
            $where[] = ['d.level_id','=',$this->params['level_id']];
        }
        if(isset($this->params['is_freeze']) && $this->params['is_freeze']){
            $where[] = ['d.is_freeze','=',$this->params['is_freeze']];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['d.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['d.create_time','<=',strtotime($this->params['end_time'])];
        }
        return $where;
    }

    /**
     * @notes 分销商列表
     * @return array
     * @author ljj
     * @date 2023/10/23 6:29 下午
     */
    public function lists(): array
    {
        $lists = Distribution::alias('d')
            ->Join('user u', 'u.id = d.user_id')
            ->field('d.id,u.avatar,u.nickname,u.account,d.level_id,u.first_leader,d.is_freeze,d.distribution_time,d.user_id')
            ->append(['level_name','earnings','wait_earnings','first_leader_name','is_freeze_desc'])
            ->where($this->where())
            ->order('d.id','desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 分销商数量
     * @return int
     * @author ljj
     * @date 2023/10/23 6:29 下午
     */
    public function count(): int
    {
        return Distribution::alias('d')
            ->Join('user u', 'u.id = d.user_id')
            ->where($this->where())
            ->count();
    }

    /**
     * @notes 导出表名
     * @return string
     * @author ljj
     * @date 2023/10/23 5:29 下午
     */
    public function setFileName(): string
    {
        return '分销商表';
    }

    /**
     * @notes 导出字段名
     * @return string[]
     * @author ljj
     * @date 2023/10/23 5:29 下午
     */
    public function setExcelFields(): array
    {
        return [
            'user_info' => '用户信息',
            'level_name' => '分销等级',
            'earnings' => '已入账佣金',
            'wait_earnings' => '待结算佣金',
            'first_leader_name' => '上级分销商',
            'is_freeze_desc' => '分销状态',
            'distribution_time' => '成为分销商时间',
        ];
    }
}