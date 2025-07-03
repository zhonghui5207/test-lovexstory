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
namespace app\api\lists;
use app\common\model\course\CourseCategory;

/**
 * 分类列表类
 * Class CourseCagegoryLists
 * @package app\api\lists
 */
class CourseCagegoryLists extends BaseApiDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {

        $lists = (new CourseCategory())->field('id,name,pid,image,status,sort')
            ->order(['sort' => 'desc'])
            ->where($this->searchWhere)
            ->where(['level' => 1,'status'=>1])
            ->with(['sons'=>function($query){
                $query->field('id,pid,name,image,status,create_time')
                ->order(['sort' => 'desc','id'=>'asc']);
            }])
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();
        foreach ($lists as $key => $category) {
            $lists[$key]['course_num'] = 0;
            foreach ($category['sons'] as $sonsKey => $sons){
                $lists[$key]['sons'][$sonsKey]['course_num'] = 0;
            }

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
        $count = (new CourseCategory())->field('id,name,pid,image,is_show')
            ->order(['sort' => 'asc', 'id' => 'desc'])
            ->where($this->searchWhere)
            ->where(['level' => 1,'status'=>1])
            ->limit($this->limitOffset, $this->limitLength)
            ->count();
        return $count;
    }





}