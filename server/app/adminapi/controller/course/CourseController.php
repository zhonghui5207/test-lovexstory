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
use app\adminapi\logic\course\CourseLogic;
use app\adminapi\validate\course\CourseValidate;

/**
 * 课程控制器类
 * Class CourseController
 * @package app\adminapi\controller\course
 */
class CourseController extends BaseAdminController
{

    /**
     * @notes 课程列表的公共接口
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/27 16:51
     */
    public function commonLists()
    {
        return $this->dataLists();

    }

    /**
     * @notes 课程列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 16:23
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 其他列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/24 9:58
     */
    public function otherLists()
    {
        $lists = (new CourseLogic())->otherLists();
        return $this->success('添加成功',$lists,1,0);
    }

    /**
     * @notes 添加课程
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 18:16
     */
    public function add()
    {
        $params = (new CourseValidate())->post()->goCheck('add');
        $result = (new CourseLogic())->add($params);
        if (true === $result) {
            return $this->success('添加成功');
        }
        return $this->fail($result);
    }

    /**
     * @notes 课程详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/20 18:30
     */
    public function detail()
    {
        $params = (new CourseValidate())->goCheck('detail');
        $detail = (new CourseLogic())->detail($params['id']);
        return $this->success('', $detail, 1, 0);
    }

    /**
     * @notes 获取课程的收费类型
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/23 15:27
     */
    public function getFeeType()
    {
        $params = (new CourseValidate())->goCheck('detail');
        $detail = (new CourseLogic())->getFeeType($params['id']);
        return $this->success('', $detail, 1, 0);
    }

    /**
     * @notes 编辑课程
     * @author cjhao
     * @date 2022/5/20 18:17
     */
    public function edit()
    {
        $params = (new CourseValidate())->post()->goCheck('edit');
        $result = (new CourseLogic())->edit($params);
        if (true === $result) {
            return $this->success('添加成功');
        }
        return $this->fail($result);
    }


    /**
     * @notes 修改状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/27 9:56
     */
    public function status()
    {
        $params = (new CourseValidate())->post()->goCheck('status');
        (new CourseLogic())->status($params);
        return $this->success('修改成功');
    }

    /**
     * @notes 设置课程精选
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 16:06
     */
    public function choice()
    {
        $params = (new CourseValidate())->post()->goCheck('choice');
        (new CourseLogic())->choice($params);
        return $this->success('修改成功');
    }

    /**
     * @notes 删除课程
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/30 11:09
     */
    public function del()
    {
        $params = (new CourseValidate())->post()->goCheck('del');
        (new CourseLogic())->del($params);
        return $this->success('删除成功');

    }

}