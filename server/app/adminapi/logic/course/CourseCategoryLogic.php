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
use app\common\model\course\CourseCategory;

/**
 * 课程分类逻辑层
 * Class CourseCategoryLogic
 * @package app\adminapi\logic\course
 */
class CourseCategoryLogic
{

    /**
     * @notes 通过级别获取等级
     * @param int $level
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/19 17:27
     */
    public function levelbyList(int $level):array
    {
        !in_array($level,[1,2]) && $level = 1;
        return CourseCategory::where(['level'=>$level])
                ->withoutField('delete_time,create_time,update_time,level')
                ->order('sort desc')
                ->select()->toArray();


    }


    /**
     * @notes 添加课程分类
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/18 18:12
     */
    public function add(array $params):bool
    {
        $courseCategory = new CourseCategory();
        $level = 1;
        $params['pid'] && $level = 2;

        $courseCategory->name       = $params['name'];
        $courseCategory->level      = $level;
        $courseCategory->image      = $params['image'] ?? '';
        $courseCategory->pid        = $params['pid'];
        $courseCategory->sort       = $params['sort'];
        $courseCategory->status     = $params['status'];
        $courseCategory->save();
        return true;
    }


    /**
     * @notes 编辑课程分类
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/18 18:26
     */
    public function edit(array $params):bool
    {
        $params['level'] = 1;
        $params['pid'] && $params['level'] = 2;
        CourseCategory::update($params);
        return true;
    }


    /**
     * @notes 获取详情
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/19 16:41
     */
    public function getDetail(int $id):array
    {
        return CourseCategory::withoutField('delete_time,create_time,update_time,level')->find($id)->toArray();

    }

    /**
     * @notes 改变分类状态
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/19 16:42
     */
    public function status(array $params):int
    {
        return CourseCategory::where(['id'=>$params['id']])->update(['status'=>$params['status']]);

    }


    /**
     * @notes 删除课程分类
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2022/5/19 10:56
     */
    public function del(int $id):int
    {
        return CourseCategory::destroy($id);
    }


}