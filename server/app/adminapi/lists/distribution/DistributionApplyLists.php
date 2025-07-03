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
use app\common\lists\ListsExcelInterface;
use app\common\lists\ListsExtendInterface;
use app\common\model\distribution\DistributionApply;
use app\common\model\user\User;


class DistributionApplyLists extends BaseAdminDataLists implements ListsExtendInterface,ListsExcelInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/23 12:06 下午
     */
    public function where(): array
    {
        $where = [];
        if(isset($this->params['user_info']) && $this->params['user_info']){
            $where[] = ['u.sn|u.nickname|u.mobile','like','%'.$this->params['user_info'].'%'];
        }
        if(isset($this->params['leader_info']) && $this->params['leader_info']){
            $leader_id = User::where('sn|nickname|mobile','like','%'.$this->params['leader_info'].'%')->value('id');
            $user_ids = User::where(['first_leader'=>$leader_id])->column('id');
            $where[] = ['u.id','in',$user_ids];
        }
        if(isset($this->params['status']) && $this->params['status'] != ''){
            $where[] = ['da.status','=',$this->params['status']];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['da.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['da.create_time','<=',strtotime($this->params['end_time'])];
        }
        return $where;
    }

    /**
     * @notes 分销申请列表
     * @return array
     * @author ljj
     * @date 2023/10/23 2:40 下午
     */
    public function lists(): array
    {
        $lists = DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->field('da.id,u.avatar,u.nickname,da.real_name,da.mobile,u.first_leader,da.status,da.audit_remark,da.create_time')
            ->append(['first_leader_name','status_desc'])
            ->where($this->where())
            ->order('da.id','desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 分销申请数量
     * @return int
     * @author ljj
     * @date 2023/10/23 2:41 下午
     */
    public function count(): int
    {
        return DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->where($this->where())
            ->count();
    }

    /**
     * @notes 数据统计
     * @return array
     * @author ljj
     * @date 2023/10/23 12:09 下午
     */
    public function extend(): array
    {
        $where = [];
        if(isset($this->params['user_info']) && $this->params['user_info']){
            $where[] = ['u.sn|u.nickname|u.mobile','like','%'.$this->params['user_info'].'%'];
        }
        if(isset($this->params['leader_info']) && $this->params['leader_info']){
            $leader_id = User::where('sn|nickname|mobile','like','%'.$this->params['leader_info'].'%')->value('id');
            $user_ids = User::where(['first_leader'=>$leader_id])->column('id');
            $where[] = ['u.id','in',$user_ids];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['da.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['da.create_time','<=',strtotime($this->params['end_time'])];
        }

        $all = DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->where($where)
            ->count();
        $wait = DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->where($where)
            ->where('status', DistributionEnum::STATUS_WAIT)
            ->count();
        $pass = DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->where($where)
            ->where('status', DistributionEnum::STATUS_PASS)
            ->count();
        $refuse = DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->where($where)
            ->where('status', DistributionEnum::STATUS_REFUSE)
            ->count();

        return [
            'all' => $all,
            'wait' => $wait,
            'pass' => $pass,
            'refuse' => $refuse,
        ];
    }

    /**
     * @notes 设置默认导出表名
     * @return string
     * @author ljj
     * @date 2023/10/23 12:09 下午
     */
    public function setFileName(): string
    {
        return '分销商申请表';
    }

    /**
     * @notes 设置导出字段名
     * @return string[]
     * @author ljj
     * @date 2023/10/23 12:09 下午
     */
    public function setExcelFields(): array
    {
        return [
            'nickname' => '用户信息',
            'real_name' => '真实姓名',
            'mobile' => '手机号',
            'first_leader_name' => '上级邀请人',
            'status_desc' => '申请状态',
            'audit_remark' => '审核说明',
            'create_time' => '申请时间',
        ];
    }
}