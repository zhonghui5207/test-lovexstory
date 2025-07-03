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
namespace app\adminapi\validate\decorate;
use app\common\model\course\Course;
use app\common\model\decorate\Subject;
use app\common\model\decorate\SubjectCourse;
use app\common\validate\BaseValidate;

/**
 * 专区专题课程验证类
 * Class SubjectCourseValidate
 * @package app\adminapi\validate\decorate
 */
class SubjectCourseValidate extends BaseValidate
{

    protected $rule = [
        'id'            => 'require|checkSubjectCourse',
        'subject_id'    => 'require|checkSubject',
        'course_ids'    => 'require|array|checkCourse',
    ];

    protected $message = [
        'id.require'            => '请选择数据',
        'subject_id.require'    => '请选择专区',
        'course_ids.require'    => '请选择课程',
        'course_ids.array'      => '课程数据错误',
    ];

    protected function sceneSave()
    {
        return $this->only(['subject_id','course_ids']);
    }

    protected function sceneDetail()
    {
        return $this->only(['subject_id']);
    }
    protected function sceneDel()
    {
        return $this->only(['id']);
    }


    protected function checkSubject($value,$rule,$data)
    {
        if(Subject::findOrEmpty($value)){
            return true;
        }
        return '专区不存在';
    }

    protected function checkSubjectCourse($value,$rule,$data)
    {
        if(SubjectCourse::findOrEmpty($value)){
            return true;
        }
        return '数据不存在';
    }

    protected function checkCourse($value,$rule,$data)
    {

        $courseIds = Course::where(['id'=>$value])->count();
        if($courseIds != count($value)){
            return '课程数据错误，请重新选择';
        }
        return true;
    }




}
