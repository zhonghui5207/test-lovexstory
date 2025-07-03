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
use app\common\model\course\Course;
use app\common\model\course\CourseMaterial;
use app\common\validate\BaseValidate;

/**
 * 课件资料验证类
 * Class CourseMaterialValidate
 * @package app\adminapi\validate\course
 */
class CourseMaterialValidate extends BaseValidate
{

    protected $rule = [
        'course_id'         => 'require|checkCourse',
        'link'              => 'require',
        'code'              => 'require',
        'is_link'           => 'require|in:0,1',
    ];

    protected $message = [
        'course_id.require'     => '请选择课程',
        'link.require'          => '请输入网盘链接',
        'code.require'          => '请输入网盘密码',
        'is_link.require'       => '请选择是否强制网盘下载',
        'is_link.in'            => '强制网盘下载值错误',
    ];

    public function sceneDetail(){
        return $this->only(['course_id']);
    }

    protected function checkCourse($value,$rule,$data)
    {

        $course = Course::findOrEmpty($value);
        if($course->isEmpty()){
            return '课程不存在';
        }
        if(isset($data['edit'])){
            $courseMaterial = CourseMaterial::where(['course_id' => $value])->findOrEmpty();
            if($courseMaterial->isEmpty()){
                return true;
            }
            if(!isset($data['id'])){
                return '缺少id';
            }
            if($data['id'] != $courseMaterial->id){
                return '数据错误';
            }
        }
        return true;

    }

}