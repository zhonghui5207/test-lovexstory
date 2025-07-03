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
namespace app\api\logic;
use app\common\model\course\Course;
use app\common\model\teacher\Teacher;

/**
 * 讲师逻辑类
 * Class TeacherLogic
 * @package app\api\logic
 */
class TeacherLogic
{

    /**
     * @notes 获取讲师详情
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2022/6/8 19:00
     */
    public function detail(int $id):array
    {
        $teacher = Teacher::withoutField('sort,create_time,update_time,delete_time')
                    ->findOrEmpty($id);

        if(!$teacher->isEmpty()){
            //讲师的课程
            $courseList = Course::where(['teacher_id'=>$id,'status'=>1])
                ->field('id,name,type,fee_type,cover,sell_price,(virtual_study_num+study_num) as study_num')
                ->withCount('catalogue')
                ->select()->toArray();
            $teacher->course_list = $courseList;
        }
        return $teacher->toArray();
    }

}