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
use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseColumn;
use think\Exception;

/**
 * 课程专栏逻辑类
 * Class CourseColumnLogic
 * @package app\adminapi\logic\course
 */
class CourseColumnLogic
{


    /**
     * @notes 添加课程专栏
     * @param array $params
     * @return bool|string
     * @throws \Exception
     * @author cjhao
     * @date 2022/5/27 19:00
     */
    public function add(array $params)
    {
        try {

            if(in_array($params['id'],$params['course_ids'])){
                return '课程专栏不能包含当前课程';
            }
            $courseLists = Course::where(['id'=>$params['course_ids']])
                    ->where('type','<>',CourseEnum::COURSE_TYPE_COLUMN)
                    ->column('fee_type','id');

            $courseIds = array_keys($courseLists);
            $data = [];
            foreach ($params['course_ids'] as $courseId)
            {
                if(!in_array($courseId,$courseIds)){
                    throw new Exception('课程数据错误，请刷新页面重新选择');
                }
                $feeType = $courseLists[$courseId] ;
                $data[] = [
                    'course_id'     => $params['id'],
                    'relation_id'   => $courseId,
                    'fee_type'      => $feeType,
                    'sort'          => 0,
                ];
            }
            (new CourseColumn)->saveAll($data);
            return true;

        }catch (Exception $e) {

            return $e->getMessage();

        }
    }

    /**
     * @notes 修改专栏收费类型
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/30 16:03
     */
    public function changeFeeType(array $params):bool
    {
        CourseColumn::where(['id'=>$params['id']])->update(['fee_type'=>$params['fee_type']]);
        return true;
    }


    /**
     * @notes 删除专栏
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2022/5/30 16:25
     */
    public function del(int $id):bool
    {
        CourseColumn::destroy($id);
        return true;
    }

}