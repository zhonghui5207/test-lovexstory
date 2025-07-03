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
use app\common\model\course\CourseCategory;
use app\common\validate\BaseValidate;

/**
 * 课程分类
 * Class CourseCategoryValidate
 * @package app\adminapi\validate\course
 */
class CourseCategoryValidate extends BaseValidate
{
    protected $rule = [
        'id'        => 'require|checkCategory',
        'name'      => 'require|max:16|unique:' . CourseCategory::class . ',name',
        'pid'       => 'require|checkPid',
        'status'    => 'require|in:0,1',
        'sort'      => 'number|max:5',
    ];

    protected $message = [
        'id.require'        => '请选择分类',
        'pid.require'       => '请选择父级',
        'name.require'      => '分类名称不能为空',
        'name.unique'       => '分类名称重复',
        'name.max'          => '分类名称字数不能超过8个',
        'sort.number'       => '排序必须为纯数字',
        'sort.max'          => '排序最大不能超过五位数',
        'status.require'    => '请选择状态',
        'status.in'         => '状态类型错误',
    ];

    public function sceneAdd()
    {
        return $this->remove('id','require');
    }

    public function sceneDel()
    {
        return $this->only(['id'])->append('id','checkDel');
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }
    public function sceneStatus()
    {
        return $this->only(['id','status'])->remove('id','checkCategory');
    }


    public function checkPid($value, $rule, $data)
    {
        //没父级，不验证
        if (empty($value)) {
            return true;
        }
        if (isset($data['id']) && $value == $data['id']) {
            return '父级不能是自己';
        }
        $pcategory = CourseCategory::find($value);
        if (!$pcategory) {
            return '父级分类不存在';
        }
        if($pcategory->level == 2){
            return '分类只允许到二级';
        }

        return true;
    }


    public function checkCategory($value, $rule, $data)
    {
        if (!(CourseCategory::find($value))) {
            return '分类不存在';
        }
        return true;
    }


    public function checkDel($value, $rule, $data)
    {
        $course = Course::where(['category_id' => $value])->findOrEmpty();
        if (!$course->isEmpty()) {
            return '已有课程绑定了该分类，不能删除';
        }
        $courseCategory = CourseCategory::where(['pid' => $value])->findOrEmpty();
        if(!$courseCategory->isEmpty()){
            return  '分类已经绑定下级，无法删除';
        }


        return true;
    }


}