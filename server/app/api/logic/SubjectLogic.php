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
use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\model\decorate\Subject;
use think\Exception;

/**
 * 专区列表
 * Class SubjectLogic
 * @package app\api\logic
 */
class SubjectLogic
{


    /**
     * @notes 专题详情
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2022/6/30 16:17
     */
    public function detail(int $id)
    {
        try{
            $detail = Subject::where(['id'=>$id,'status'=>1])
                    ->with(['course'])
                    ->field('id,name,cover,image')
                    ->findOrEmpty();
            if($detail->isEmpty()){
                throw new Exception('该专题已经下线');
            }
            $courseIds = array_column($detail->course->toArray(),'course_id');
            $courseLists = Course::where(['id'=>$courseIds])
                            ->field('id,name,type,cover,fee_type,sell_price,(virtual_study_num+study_num) as study_num')
                            ->withCount('catalogue')
                            ->append(['type_desc'])
                            ->select()->toArray();
            $courseLists = array_column($courseLists,null,'id');
            $studyNum = 0;
            foreach ($detail->course as $key => $course){

                $detail->course[$key] = $courseLists[$course->course_id] ?? [];
                $studyNum+=$detail->course[$key]['study_num'];

            }
            $detail->study_num = $studyNum;
            return $detail->toArray();
        }catch (\Exception $e){
            return $e->getMessage();
        }


    }

}