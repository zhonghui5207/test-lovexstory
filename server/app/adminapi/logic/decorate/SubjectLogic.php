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

/**
 * 专区专题逻辑层
 * Class SubjectLogic
 * @package app\adminapi\logic\decorate
 */
class SubjectLogic
{

    /**
     * @notes 其他列表
     * @return array
     * @author cjhao
     * @date 2022/5/31 18:58
     */
    public function otherLists(): array
    {
        $subjectLists = Subject::column('name', 'id');
        $typeLists = CourseEnum::getCouserTypeDesc();
        return ['subject_list' => $subjectLists, 'typeLists' => $typeLists];
    }

    /**
     * @notes 添加专区
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/31 18:12
     */
    public function add(array $params): bool
    {
        (new Subject())->save($params);
        return true;
    }

    /**
     * @notes 修改专区
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/31 18:37
     */
    public function edit(array $params): bool
    {
        Subject::update($params, ['id' => $params['id']], ['name', 'cover', 'image', 'sort', 'status']);
        return true;
    }

    /**
     * @notes 获取详情
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2022/6/1 15:45
     */
    public function detail(int $id): array
    {
        $detail = Subject::withoutField('create_time,update_time,delete_time')->findOrEmpty($id);
        return $detail->toArray();
    }

    /**
     * @notes 删除专区
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2022/5/31 18:40
     */
    public function del(int $id): bool
    {
        Subject::destroy($id);
        return true;
    }

    /**
     * @notes 修改专区状态
     * @param array $params
     * @author cjhao
     * @date 2022/7/7 15:52
     */
    public function status(array $params)
    {
        Subject::where(['id'=>$params['id']])->update(['status'=>$params['status']]);

    }


}