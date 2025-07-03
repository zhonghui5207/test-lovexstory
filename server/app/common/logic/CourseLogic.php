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
namespace app\common\logic;
use app\common\model\course\Course;
use app\common\model\course\StudyCourse;
use app\common\model\order\Order;

/**
 * 课程公共逻辑类
 * Class CourseLogic
 * @package app\common\logic
 */
class CourseLogic
{
    /**
     * @notes 课程统计
     * @param int $userId
     * @param int $courseId
     * @param int $orderId
     * @param int $type:1-加入课程；0-回收课程
     * @return bool
     * @author cjhao
     * @date 2022/7/1 10:36
     */
    public static function courseStatistics(int $userId, int $courseId,int $orderId = 0,int $type = 1):bool
    {
        $studyCourse = StudyCourse::where(['course_id'=>$courseId,'user_id'=>$userId])
                    ->findOrEmpty();
        if($type){
            //加入课程学习
            if($studyCourse->isEmpty()){
                $studyCourse = new StudyCourse();
                $studyCourse->user_id   = $userId;
                $studyCourse->course_id = $courseId;
                $studyCourse->source_id = $orderId;
                $studyCourse->save();
            }

        }else{
            if(!$studyCourse->isEmpty()){
                $studyCourse->delete();
            }

        }
        //重新计算学习人数，并更新课程表的学习人数
        $studyCount = $studyCourse::where(['course_id'=>$courseId,'user_id'=>$userId])->count();
        Course::where(['id'=>$courseId])->update(['study_num'=>$studyCount]);
        return true;
    }
}