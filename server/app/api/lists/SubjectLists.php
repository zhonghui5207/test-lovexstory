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
namespace app\api\lists;
use app\common\lists\BaseDataLists;
use app\common\model\course\Course;
use app\common\model\decorate\Subject;

/**
 * 专区列表类
 * Class SubjectController
 * @package app\api\lists
 */
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
        $subjectLists = Subject::where(['status' => 1])
            ->with(['course'])
            ->field('id,name,cover,image,create_time')
            ->limit($this->limitOffset, $this->limitLength)
            ->select();

        $courseLists = Course::column('(virtual_study_num+study_num) as study_num','id');

        foreach ($subjectLists as $subject){
            $courseIds = array_column($subject->course->toarray(),'course_id');
            $studyNum = 0;
            foreach ($courseIds as $courseId){
                $studyNum+= $courseLists[$courseId] ?? 0;
            }
            $subject->study_num = $studyNum;
        }

        return $subjectLists->hidden(['course'])->toArray();

    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        return Subject::where(['status' => 1])->count();

    }
}