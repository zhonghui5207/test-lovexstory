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
namespace app\adminapi\logic\decorate;
use app\common\enum\CourseEnum;
use app\common\model\decorate\Subject;
use app\common\model\decorate\SubjectCourse;
use app\common\model\teacher\Teacher;
use think\facade\Db;

/**
 * 专题课程逻辑层
 * Class SubjectCourseLogic
 * @package app\adminapi\logic\decorate
 */
class SubjectCourseLogic
{

    /**
     * @notes 添加专区课程
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/6/1 14:40
     */
    public function save(array $params):bool
    {
        try{
            Db::startTrans();

            SubjectCourse::where(['subject_id'=>$params['subject_id']])->delete();

            $data = [];
            foreach ($params['course_ids'] as $courseId)
            {
                $data[] = [
                    'subject_id'    => $params['subject_id'],
                    'course_id'     => $courseId,
                ];
            }

            SubjectCourse::insertAll($data);
            Db::commit();
            return true;

        }catch (\Exception $e){

            Db::rollback();
            return $e->getMessage();
        }


    }

    /**
     * @notes 获取详情
     * @param int $subjectId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/6/2 11:17
     */
    public function detail(int $subjectId):array
    {
        $lists = SubjectCourse::field('subject_id,course_id')
            ->where(['subject_id' => $subjectId])
            ->with(['course'])->hidden(['id'])->select()->toArray();

        $lists && $teacherList = Teacher::where(['id'=>array_column($lists,'teacher_id')])->column('name','id');

        foreach ($lists as $key => $list){

            $lists[$key]['teacher_name'] = $teacherList[$list['teacher_id']] ?? '';
            $lists[$key]['type_desc'] = CourseEnum::getCouserTypeDesc($list['type']);
        }
        return $lists;

    }

    /**
     * @notes 删除专区课程
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2022/6/1 14:41
     */
    public function del(int $id):bool
    {
        return SubjectCourse::destroy($id);

    }

}