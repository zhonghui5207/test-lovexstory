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
use app\common\enum\CourseEnum;
use app\common\lists\BaseDataLists;
use app\common\model\decorate\Subject;
use app\common\model\decorate\SubjectCourse;

/**
 * 专区课程列表类
 * Class SubjectCourseLists
 * @package app\adminapi\lists\decorate
 */
class SubjectCourseLists extends BaseDataLists {

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = SubjectCourse::alias('SC')
                ->join('course C','SC.course_id = C.id')
                ->where($this->setSearch())
                ->field('SC.id,C.name,C.type,C.status,C.sort')
                ->limit($this->limitOffset, $this->limitLength)
                ->select();
        $subjectName = Subject::column('name','id');

        foreach ($lists as $course){
            $course['subject_name'] = $subjectName[$course['id']] ?? '';
            $course['type_desc'] = CourseEnum::getCouserTypeDesc($course['type']);
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

        $count = SubjectCourse::alias('SC')
            ->join('course C','SC.course_id = C.id')
            ->where($this->setSearch())
            ->count();

        return $count;

    }


    public function setSearch(): array
    {
        $where = [];
        if(isset($this->params['name']) && $this->params['name']) {
            $where[] = ['C.name','like','%'.$this->params['name'].'%'];
        }
        if(isset($this->params['type']) && $this->params['type']){
            $where[] = ['C.type','=',$this->params['type']];
        }
        if(isset($this->params['subject_id']) && $this->params['subject_id']){
            $where[] = ['SC.subject_id','=',$this->params['subject_id']];
        }
        return $where;
    }
}