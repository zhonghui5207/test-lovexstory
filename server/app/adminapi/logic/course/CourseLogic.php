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
namespace app\adminapi\logic\course;
use app\common\enum\CourseImage;
use app\common\enum\OrderEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseCatalogue;
use app\common\model\course\CourseCategory;
use app\common\model\distribution\DistributionCourse;
use app\common\model\order\Order;
use app\common\model\teacher\Teacher;
use think\facade\Db;
use think\Exception;

/**
 * 课程逻辑类
 * Class CourseLogic
 * @package app\adminapi\logic\course
 */
class CourseLogic
{
    /**
     * @notes 添加课程
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/20 17:17
     */
    public function add(array $params)
    {
        Db::startTrans();
        try {

            $course = new Course();
            $course->name               = $params['name'];
            $course->type               = $params['type'];
            $course->category_id        = $params['category_id'];
            $course->teacher_id         = $params['teacher_id'];
            $course->synopsis           = $params['synopsis'];
            $course->cover              = $params['cover'];
            $course->content            = $params['content'];
            $course->fee_type           = $params['fee_type'];
            $course->sell_price         = $params['fee_type'] > 0 ? $params['sell_price']: 0;
            $course->line_price         = $params['line_price'] ?? 0;
            $course->virtual_study_num  = $params['virtual_study_num'] ?? 0;
            $course->status             = $params['status'];
            $course->sort               = $params['sort'];
            $course->is_choice          = $params['is_choice'];
            $course->save();
            $images = $params['images'];
            if($images){

                array_walk($images, function (&$image){
                    $image = ['uri' => $image];
                });
                $course->courseImage()->saveAll($images);
            }
            Db::commit();
            return true;

        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 获取课程详情
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/20 18:36
     */
    public function detail(int $id):array
    {
        $course = Course::withoutField('create_time,update_time,delete_time,type')
                ->append(['images'])
                ->find($id);
        return $course->toArray();
    }


    /**
     * @notes 获取课程收费类型
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/23 15:25
     */
    public function getFeeType(int $id):array
    {
        $feeType = Course::field('fee_type')->findOrEmpty($id)->toArray();
        return $feeType;
    }


    /**
     * @notes 编辑课程
     * @param array $params
     * @return array
     * @author cjhao
     * @date 2022/5/20 18:23
     */
    public function edit(array $params)
    {
        Db::startTrans();
        try {

            $course = Course::find($params['id']);
            //如果是收费课程，然后改成免费
            if(1 == $course->fee_type && 0 == $params['fee_type']){
                //更新课程下的目录都为免费
                CourseCatalogue::where(['course_id'=>$course->id])->update([
                    'fee_type'  => 0,
                ]);
            }

            $course->name               = $params['name'];
            $course->type               = $params['type'];
            $course->category_id        = $params['category_id'];
            $course->teacher_id         = $params['teacher_id'];
            $course->synopsis           = $params['synopsis'];
            $course->cover              = $params['cover'];
            $course->content            = $params['content'];
//            $course->fee_type           = $params['fee_type'];
            $course->sell_price         = $params['fee_type'] > 0 ? $params['sell_price']: 0;
            $course->line_price         = $params['line_price'] ?? 0;
            $course->virtual_study_num  = $params['virtual_study_num'] ?? 0;
            $course->status             = $params['status'];
            $course->sort               = $params['sort'];
            $course->is_choice          = $params['is_choice'];
            $course->distribution_status= 0;
            $course->distribution_rule  = 1;
            $course->save();
            $images = $params['images'];
            CourseImage::where(['course_id'=>$course->id])->delete();
            if($images){
                array_walk($images, function (&$image){
                    $image = ['uri' => $image];
                });
                $course->courseImage()->saveAll($images);
            }

            //删除分销信息
            DistributionCourse::where(['course_id'=>$params['id']])->delete();

            Db::commit();
            return true;

        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 其他列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/24 9:58
     */
    public function otherLists():array
    {
        $categoryList = CourseCategory::where(['level'=>1])->with(['sons'=>function($query){
                            $query->field('id,pid,name');
                        }])
                        ->field('id,pid,name')
                        ->select();

        $teacherList = Teacher::where(['status'=>1])
                        ->field('id,name')
                        ->select();

        return ['category_list'=>$categoryList,'teacher_list'=>$teacherList];
    }

    /**
     * @notes 修改课程状态
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/27 9:29
     */
    public function status(array $params):bool
    {
        Course::where(['id'=>$params['id']])->update(['status'=>$params['status']]);
        return true;

    }

    /**
     * @notes 设置课程精选
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/31 16:07
     */
    public function choice(array $params):bool
    {
        Course::where(['id'=>$params['id']])->update(['is_choice'=>$params['is_choices']]);
        return true;
    }

    /**
     * @notes 删除课程
     * @param $params
     * @author cjhao
     * @date 2022/6/30 11:15
     */
    public function del($params)
    {
        Course::destroy($params['id']);
        return true;
    }


}