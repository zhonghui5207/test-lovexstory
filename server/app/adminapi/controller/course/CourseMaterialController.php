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
namespace app\adminapi\controller\course;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\course\CourseMaterialLogic;
use app\adminapi\validate\course\CourseMaterialValidate;

/**
 * 课程资料控制器类
 * Class CourseMaterialController
 * @package app\adminapi\controller\course
 */
class CourseMaterialController extends BaseAdminController
{

    /**
     * @notes 获取课程资料
     * @param int $courseId
     * @return array
     * @author cjhao
     * @date 2022/5/23 17:00
     */
    public function detail()
    {
        $params = (new CourseMaterialValidate())->goCheck('detail');
        $detail = (new CourseMaterialLogic())->detail($params['course_id']);
        return $this->success('',$detail,1,0);
    }

    /**
     * @notes 设置课程资料
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/23 17:11
     */
    public function set()
    {
        $params = (new CourseMaterialValidate())->post()->goCheck('',['edit'=>1]);
        (new CourseMaterialLogic())->set($params);
        return $this->success('保存成功');
    }
}