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
namespace app\adminapi\lists\decorate;
use app\common\model\decorate\Subject;
use app\common\lists\BaseDataLists;

class SubjectLists extends BaseDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = Subject::withCount('course')
                ->where($this->setWhere())
                ->field('id,name,cover,sort,status,create_time')
                ->limit($this->limitOffset, $this->limitLength)
                ->order('sort desc,id desc')
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
        return Subject::where($this->setWhere())->count();
    }

    /**
     * @notes 设置条件
     * @return array
     * @author cjhao
     * @date 2022/10/28 18:53
     */
    public function setWhere()
    {
        $where = [];
        if(isset($this->params['status']) && ''!= $this->params['status']){
            $where[] = ['status','=',$this->params['status']];
        }
        return $where;

    }
}