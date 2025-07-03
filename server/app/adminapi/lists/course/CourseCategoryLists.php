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
use app\common\lists\ListsSearchInterface;
use app\common\model\course\Course;
use app\common\model\course\CourseCategory;
use app\common\service\FileService;

class CourseCategoryLists extends BaseAdminDataLists
{


    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $courseCategoryList = CourseCategory::where($this->setSearch())->select()->toArray();
        $categoryNum = Course::group('category_id')->column('count(category_id) as num','category_id');
        $lists = linear_to_tree($courseCategoryList,'sons');
        //排序
        $sort = array_column($lists,'sort');
        array_multisort($sort,SORT_DESC,$lists);
        foreach ($lists as $key => $categoryList){
            $totalNum = $categoryNum[$categoryList['id']] ?? 0;
            $subList = [];
            if(isset($categoryList['sons'])){
                foreach ($categoryList['sons'] as $list){
                    $num = $categoryNum[$list['id']] ?? 0;
                    $totalNum += $num;
                    $list['course_num'] = $num;
                    $subList[] = $list;
                }
            }
            //排序
            $sort = array_column($subList,'sort');
            array_multisort($sort,SORT_DESC,$subList);
            $lists[$key]['course_num'] = $totalNum;
            $lists[$key]['sons'] = $subList;
        }
        return $lists;

    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        $count =CourseCategory::where($this->setSearch())
            ->count();

        return $count;
    }

    /**
     * @notes 设置搜索条件
     * @return array
     * @author 令狐冲
     * @date 2021/7/7 19:44
     */
    public function setSearch(): array
    {
        $where = [];
//        if(isset($this->params['name']) && $this->params['name']){
//            $where[] = ['C1.name|C2.name','like','%'.$this->params['name'].'%'];
//        }
//        if(isset($this->params['status']) && '' != $this->params['status']){
//            $where[] = ['C1.status','=',$this->params['status']];
//        }
        if(isset($this->params['name']) && $this->params['name']){
            $where[] = ['name','like','%'.$this->params['name'].'%'];
        }
        if(isset($this->params['status']) && '' != $this->params['status']){
            $where[] = ['status','=',$this->params['status']];
        }
        return $where;
    }
}