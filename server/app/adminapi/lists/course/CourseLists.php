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
namespace app\adminapi\lists\course;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseCatalogue;
use app\common\model\course\CourseCategory;
use app\common\model\teacher\Teacher;
use think\Model;

/**
 * 课程列表类
 * Class CourseLists
 * @package app\adminapi\lists\course
 */
class CourseLists extends BaseAdminDataLists
{

    /**
     * @notes 列表
     * @return array
     * @author cjhao
     * @date 2022/5/20 10:02
     */
    public function lists(): array
    {
        $list = Course::alias('C')
            ->leftjoin('course_category CC', 'C.category_id = CC.id')
            ->where($this->setSearch())
            ->field("C.id,C.type,C.name,(C.study_num+C.virtual_study_num) as study_num, C.fee_type,C.category_id,C.cover,C.fee_type,C.sell_price,C.status,C.sort,CC.name as category_name")
            ->limit($this->limitOffset, $this->limitLength)
            ->order('C.sort desc,C.id desc')
            ->select();


        return $list->toArray();
    }

    /**
     * @notes 合计
     * @return int
     * @author cjhao
     * @date 2022/5/20 10:02
     */
    public function count(): int
    {
        $count = Course::alias('C')
            ->join('course_category CC', 'C.category_id = CC.id')
            ->where($this->setSearch())
            ->count();

        return $count;
    }

    /**
     * @notes 搜索条件
     * @return array
     * @author cjhao
     * @date 2022/5/20 10:02
     */
    public function setSearch(): array
    {
        $type = $this->params['type'] ?? CourseEnum::COURSE_TYPE_IMGAE_TXT;
        $where[] = ['type','=',$type];
        if (isset($this->params['name']) && $this->params['name']) {
            $where[] = ['C.name', 'like', '%'.$this->params['name'].'%'];
        }
        if (isset($this->params['teacher_name']) && $this->params['teacher_name']) {
            $teacherIds = Teacher::where('name','like','%'.$this->params['teacher_name'].'%')->value('id');
            $where[] = ['C.teacher_id','in',$teacherIds];
        }
        if(isset($this->params['category_id']) && $this->params['category_id']){
            $category = (new CourseCategory())
                ->where(['id'=>$this->params['category_id']])
                ->findOrEmpty();
            $categoryPid = [];
            if(!$category->isEmpty() && 1 == $category->level){
                $categoryPid =  (new CourseCategory())
                    ->where(['pid'=>$category->id])
                    ->column('id');
            }
            $categoryIds = array_merge([$category->id],$categoryPid);

            if($categoryIds){
                $where[] = ['C.category_id','in',$categoryIds];
            }
        }
        if(isset($this->params['status']) && '' != $this->params['status']){
            $where[] = ['C.status','=',$this->params['status']];
        }
        if(isset($this->params['fee_type']) && '' != $this->params['fee_type']){
            $where[] = ['C.fee_type','=',$this->params['fee_type']];
        }
        return $where;

    }
}