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
namespace app\adminapi\lists\teacher;
use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\course\Course;
use app\common\model\teacher\Teacher;

class TeacherLists extends BaseAdminDataLists implements ListsSearchInterface
{

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
            '='         => ['status'],
        ];

    }

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = Teacher::where($this->searchWhere)
                ->field('id,number,name,avatar,gender,status,sort,create_time')
                ->limit($this->limitOffset, $this->limitLength)
                ->order('sort desc,id desc')
                ->select();

        $teacherIds = array_column($lists->toArray(),'id');
        $courseLists = Course::where(['teacher_id'=>$teacherIds])
            ->group('teacher_id')
            ->column('count(id) as course_num,sum(study_num+virtual_study_num) as study_num','teacher_id');
        
        foreach ($lists as $teacher)
        {
            $teacher['course_num'] = $courseLists[$teacher['id']]['course_num'] ?? 0;
            $teacher['study_num'] = $courseLists[$teacher['id']]['study_num'] ?? 0;
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

        return Teacher::where($this->searchWhere)->count();
    }
}