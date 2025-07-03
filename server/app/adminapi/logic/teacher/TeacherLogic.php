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
namespace app\adminapi\logic\teacher;
use app\common\model\teacher\Teacher;

/**
 * 讲师逻辑层
 * Class TeacherLogic
 * @package app\adminapi\logic\teacher
 */
class TeacherLogic
{
    /**
     * @notes 添加讲师
     * @param array $params
     * @return int
     * @author cjhao
     * @date 2022/5/20 11:15
     */
    public function add(array $params):bool
    {
        $params['number'] = generate_sn(new Teacher(),'number',false,'',8);
        Teacher::create($params);
        return true;
    }

    /**
     * @notes 获取讲师
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/20 11:15
     */
    public function detail(int $id):array
    {
        $teacher = Teacher::withoutField('number,create_time,update_time,delete_time')->findOrEmpty($id);
        $gender = $teacher->getData('gender');
        $teacher = $teacher->toArray();
        $teacher['gender'] = $gender;
        return $teacher;
    }

    /**
     * @notes 编辑讲师
     * @param array $params
     * @return array
     * @author cjhao
     * @date 2022/5/20 11:15
     */
    public function edit(array $params):bool
    {
        Teacher::update([
                'id'        => $params['id'],
                'name'      => $params['name'],
                'avatar'    => $params['avatar'],
                'gender'    => $params['gender'],
                'synopsis'  => $params['synopsis'],
                'introduce' => $params['introduce'],
                'status'    => $params['status'],
                'sort'      => $params['sort'],
            ]);
        return true;
    }

    /**
     * @notes 删除讲师
     * @param int $id
     * @return int
     * @author cjhao
     * @date 2022/5/20 11:15
     */
    public function del(int $id):int
    {
        return Teacher::destroy($id);
    }

    /**
     * @notes 修改状态
     * @param array $params
     * @return Teacher
     * @author cjhao
     * @date 2022/5/20 11:15
     */
    public function status(array $params)
    {
        return Teacher::where(['id'=>$params['id']])->update(['status'=>$params['status']]);
    }

}