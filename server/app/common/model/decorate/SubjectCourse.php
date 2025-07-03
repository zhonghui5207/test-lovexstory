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
namespace app\common\model\decorate;
use app\common\model\BaseModel;
use app\common\model\course\Course;

/**
 * 专题专区课程模型类
 * Class Subject
 * @package app\adminapi\model\decorate
 */
class SubjectCourse extends BaseModel
{
    /**
     * @notes 关联的专区
     * @return \think\model\relation\BelongsTo
     * @author cjhao
     * @date 2022/6/1 11:22
     */
    public function subject()
    {
        return $this->hasOne(Subject::class,'id','subject_id');
    }


    /**
     * @notes 关联课程
     * @return \think\model\relation\HasOne
     * @author cjhao
     * @date 2022/6/1 11:30
     */
    public function course()
    {
        return $this->hasOne(Course::class,'id','course_id')
                ->bind(['id','course_name'=>'name','cover','sell_price','type','status','sort','teacher_id']);
    }

    public function courseLists()
    {
        return $this->hasOne(Course::class,'id','course_id')
            ->bind(['id','virtual_study_num','study_num']);
    }


}