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
namespace app\adminapi\lists\course;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\course\CourseCatalogue;

class CourseCatalogueLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = CourseCatalogue::where($this->searchWhere)
            ->where(['course_id'=>$this->params['course_id'] ?? 0])
            ->field('id,name,status,fee_type,sort,create_time')
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
        $count = CourseCatalogue::where($this->searchWhere)
            ->where(['course_id'=>$this->params['course_id'] ?? 0])
            ->count();
        return $count;
    }

    /**
     * @notes 设置搜索条件
     * @return array
     * @author 令狐冲
     * @date 2021/7/7 19:44
     */
    public function setSearch(): array
    {
        return [
            '%like%'    => ['name'],
            '='         => ['fee_type','status'],
        ];
    }
}