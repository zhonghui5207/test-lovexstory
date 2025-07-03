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
namespace app\adminapi\validate\course;

use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\validate\BaseValidate;

/**
 * 课程专栏验证类
 * Class CourseColumnValidate
 * @package app\adminapi\validate\course
 */
class CourseColumnValidate extends BaseValidate
{
    protected $rule = [
        'id'            => 'require|checkCourse',
        'course_ids'    => 'require|array',
        'fee_type'      => 'require|in:0,1'
    ];

    protected $message = [
        'id.require'            => '请选择专栏',
        'course_ids.require'    => '请选择课程',
        'course_ids.array'      => '数据错误',
        'column_id.require'     => '请选择专栏',
        'fee_type.require'      => '收费类型不能为空',
        'fee_type.in'           => '收费类型错误',
    ];

    protected function sceneAdd()
    {
        return $this->only(['id','course_ids']);
    }

    protected function sceneFeeType()
    {
        return $this->only(['id','fee_type'])->remove('id','checkCourse');
    }

    protected function sceneDel()
    {
        return $this->only(['id'])->remove('id','checkCourse');;
    }

    protected function checkCourse($value, $rule, $data)
    {
        $course =  Course::where(['type'=>CourseEnum::COURSE_TYPE_COLUMN,'id'=>$value])->findOrEmpty();
        if($course->isEmpty()){
            return '课程不存在';
        }
        return true;
    }
}