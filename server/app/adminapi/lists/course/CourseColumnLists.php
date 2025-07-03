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
use app\common\enum\CourseEnum;
use app\common\lists\BaseDataLists;
use app\common\model\course\CourseColumn;

/**
 * 课程专栏列表类
 * Class CourseColumnLists
 * @package app\adminapi\lists\course
 */
class CourseColumnLists extends BaseDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = CourseColumn::alias('CC')
                ->join('course C','CC.relation_id = C.id')
                ->withSearch($this->setSearch(), $this->params)
                ->field('CC.id,C.id as course_id,C.name,C.type,C.sell_price,C.status,CC.sort,CC.fee_type')
                ->limit($this->limitOffset, $this->limitLength)
                ->select();
        foreach ($lists as $courseColumn) {
            $courseColumn->study_num = 0;
            $courseColumn->type_desc = CourseEnum::getCouserTypeDesc($courseColumn->type);
        }
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
        $count = CourseColumn::alias('CC')
            ->join('course C','CC.relation_id = C.id')
            ->withSearch($this->setSearch(), $this->params)
            ->count();

        return $count;
    }

    public function setSearch(): array
    {
        $this->params['course_id'] = $this->params['course_id'] ?? 0;
        return array_intersect(array_keys($this->params),['course_id','name','type']);

    }
}