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
namespace app\api\validate;
use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\model\course\StudyCourse;
use app\common\validate\BaseValidate;

/**
 * 课程操作验证类
 * Class CourseHandleValidate
 * @package app\api\validate
 */
class CourseHandleValidate extends BaseValidate
{
    protected $rule = [
        'id'                => 'require|checkCourse',
        'collect'           => 'require|in:0,1',
        'catalogue_id'      => 'require',
        'schedule'          => 'require|float|egt:0'
    ];

    protected $message = [
        'id.require'                => '请选择课程',
        'collect.require'           => '参数错误',
        'collect.in'                => '操作错误',
        'catalogue_id.require'      => '参数缺失',
        'schedule.require'      => '缺少进度参数',
        'schedule.float'      => '进度参数值错误',
        'schedule.egt'      => '进度参数必须大于等于0',
    ];

    protected function sceneCollect()
    {
        return $this->only(['id','collect']);
    }

    protected function sceneStudy()
    {
        return $this->only(['id']);
    }

    protected function sceneUpdateSchedule()
    {
        return $this->only(['catalogue_id','schedule']);
    }


    protected function checkCourse($value,$rule,$data)
    {
        $course = Course::findOrEmpty($value);
        if($course->isEmpty()){
            return '课程不存在';
        }
        if(!$course->status){
            return '课程已下架';
        }

        if(isset($data['type']) && 'study' == $data['type'] ){
            if(CourseEnum::FEE_TYPE_FEE == $course->fee_type){
                return '收费课程请先购买';
            }
            $studyCourse = StudyCourse::where(['user_id'=>$data['user_id'],'course_id'=>$data['id']])
                ->findOrEmpty();

            if(!$studyCourse->isEmpty()){
                return '课程已在学习中';
            }
        }
        return true;

    }
}