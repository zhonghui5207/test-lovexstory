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
namespace app\common\model\course;

use app\common\model\BaseModel;

/**
 * 课程专栏模型类
 * Class CourseColumn
 * @package app\common\model\course
 */
class CourseColumn extends BaseModel
{

    public function searchCourseIdAttr($query,$value,$data)
    {
        $query->where('CC.course_id','=',$value);
    }

    public function searchNameAttr($query,$value,$data)
    {
        $query->where('C.name','like','%'.$value.'%');
    }
    public function searchTypeAttr($query,$value,$data)
    {
        $query->where('C.type','=',$value);
    }


    public function catalogue()
    {
        return $this->hasMany(Course::class,'id','course_id');
    }
}