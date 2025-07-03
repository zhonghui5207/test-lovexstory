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
namespace app\adminapi\lists\user;
use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\distribution\Distribution;
use app\common\model\user\User;

class UserLists extends BaseAdminDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $field = "id,sn,nickname,sex,avatar,account,mobile,channel,create_time,user_money,user_earnings";
        $lists = User::where($this->setSearch())
            ->limit($this->limitOffset, $this->limitLength)
            ->field($field)
            ->append(['is_distributor'])
            ->order('id desc')
            ->select();

        return $lists->toArray();

    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        return User::where($this->setSearch())->count();
    }

    /**
     * @notes 设置搜索条件
     * @return array
     * @author 令狐冲
     * @date 2021/7/7 19:44
     */
    public function setSearch(): array
    {
        $where = [];
        if(isset($this->params['keyword']) && $this->params['keyword']){
            $where[] = ['sn|nickname|mobile','like','%'.$this->params['keyword'].'%'];
        }
        if(isset($this->params['create_time']) && $this->params['create_time']){
            $where[] = ['create_time','>=',strtotime($this->params['create_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['create_time','<=',strtotime($this->params['end_time'])];
        }
        if(isset($this->params['channel']) && $this->params['channel']){
            $where[] = ['channel','=',$this->params['channel']];
        }
        if(isset($this->params['isDistribution']) && $this->params['isDistribution'] != ''){
            $user_ids = Distribution::where(['is_distribution'=>1])->column('user_id');
            if ($this->params['isDistribution'] == 1) {
                $where[] = ['id','in',$user_ids];
            } else {
                $where[] = ['id','notin',$user_ids];
            }
        }
        return $where;
    }
}