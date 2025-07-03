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
use app\adminapi\logic\course\CourseCategoryLogic;
use app\adminapi\validate\course\CourseCategoryValidate;
use app\common\service\JsonService;

/**
 * 课程分类控制器
 * Class CourseCategory
 * @package app\adminapi\controller\course
 */
class CourseCategoryController extends BaseAdminController
{


    /**
     * @notes 课程分类列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/19 17:21
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 通过级别获取列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/19 17:24
     */
    public function levelByList()
    {
        $level = $this->request->get('level',1);
        $result = (new CourseCategoryLogic())->levelbyList($level);
        return JsonService::success('',$result,1,0);
    }

    /**
     * @notes 添加课程分类
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/18 18:13
     */
    public function add()
    {
        $params = (new CourseCategoryValidate())->post()->goCheck('add');
        (new CourseCategoryLogic())->add($params);
        return JsonService::success('添加成功');
    }



    /**
     * @notes 编辑课程分类
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/18 18:26
     */
    public function edit()
    {
        $params = (new CourseCategoryValidate())->post()->goCheck('edit');
        (new CourseCategoryLogic())->edit($params);
        return JsonService::success('修改成功');
    }


    /**
     * @notes 修改分类显示状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/19 17:18
     */
    public function status()
    {
        $params = (new CourseCategoryValidate())->post()->goCheck('status');
        (new CourseCategoryLogic())->status($params);
        return JsonService::success('修改成功');
    }

    /**
     * @notes 获取课程分类
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/19 11:43
     */
    public function detail()
    {
        $params = (new CourseCategoryValidate())->goCheck('detail');
        $detail = (new CourseCategoryLogic())->getDetail($params['id']);
        return JsonService::success('', $detail,1,0);
    }


    /**
     * @notes 删除课程分类
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/19 10:55
     */
    public function del()
    {
        $params = (new CourseCategoryValidate())->post()->goCheck('del');
        (new CourseCategoryLogic())->del($params['id']);
        return JsonService::success('删除成功');

    }


}