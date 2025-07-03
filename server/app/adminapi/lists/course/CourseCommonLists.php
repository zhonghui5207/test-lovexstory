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
use app\common\lists\BaseDataLists;
use app\common\model\course\Course;

/**
 * 课程公共列表类
 * Class CourseCommonLists
 * @package app\adminapi\lists\course
 */
class CourseCommonLists extends BaseDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = Course::withSearch($this->setSearch(), $this->params)
                ->with(['teacherName'])
                ->field('id,name,cover,type,teacher_id,sell_price,status,is_choice')
                ->where(['status'=>1])
                ->limit($this->limitOffset, $this->limitLength)
                ->append(['type_desc'])
                ->select();

        return $lists->toArray();

    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        return Course::where(['status'=>1])->withSearch($this->setSearch(), $this->params)->count();
    }

    /**
     * @notes 设置搜索
     * @return array
     * @author cjhao
     * @date 2022/5/27 16:50
     */
    public function setSearch():array
    {
        return array_intersect(array_keys($this->params),['name','id','type']);

    }

}