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
namespace app\adminapi\validate\teacher;

use app\common\model\course\Course;
use app\common\validate\BaseValidate;

/**
 * 讲师验证类
 * Class TeacherValidate
 * @package app\adminapi\validate\teacher
 */
class TeacherValidate extends BaseValidate
{

    protected $rule = [
        'id'            => 'require',
        'name'          => 'require|max:16|checkName',
        'avatar'        => 'require',
        'gender'        => 'require|in:1,2',
        'status'        => 'require|in:0,1',
        'sort'          => 'egt:0'
    ];

    protected $message = [
        'id.require'        => '请选择讲师',
        'name.require'      => '请输入姓名',
        'name.max'          => '姓名不能超过16个字符',
        'avatar.require'    => '请上传头像',
        'gender.require'    => '请选择性别',
        'gender.in'         => '性别值错误',
        'status.require'    => '请选择状态',
        'status.in'         => '状态值错误',
        'sort.egt'          => '排序必须大于零',
    ];

    public function sceneAdd()
    {
        return $this->remove('id','require');
    }

    public function sceneId()
    {
        return $this->only(['id'])->append('id','checkTeacher');
    }

    public function sceneStatus()
    {
        return $this->only(['id','status']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);

    }

    public function checkName($value,$rule,$data)
    {
        $name = trim($value);
        if(empty($name)){
            return '教师名称不能为空';
        }
        return true;
    }


    public function checkTeacher($value,$rule,$data)
    {
        $teacher = Course::where(['teacher_id'=>$value])->findOrEmpty();
        if(!$teacher->isEmpty()) {
            return '教师绑定了课程，无法删除';
        }
        return true;
    }
}