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
use app\adminapi\logic\course\CourseColumnLogic;
use app\adminapi\validate\course\CourseColumnValidate;

/**
 * 课程专栏控制器类
 * Class CourseColumnController
 * @package app\adminapi\controller\course
 */
class CourseColumnController extends BaseAdminController
{

    /**
     * @notes 专栏列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/27 18:08
     */
    public function lists()
    {
        return $this->dataLists();

    }

    /**
     * @notes 添加课程专栏
     * @return \think\response\Json
     * @throws \Exception
     * @author cjhao
     * @date 2022/5/27 19:00
     */
    public function add()
    {
        $params = (new CourseColumnValidate())->post()->goCheck('add');
        $result = (new CourseColumnLogic())->add($params);
        if(true === $result){
            return $this->success('添加成功');
        }
        return $this->fail($result);
    }


    /**
     * @notes 修改收费类型
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/30 16:02
     */
    public function changeFeeType()
    {
        $params = (new CourseColumnValidate())->post()->goCheck('FeeType');
        (new CourseColumnLogic())->changeFeeType($params);
        return $this->success('修改成功');

    }

    /**
     * @notes 删除专栏
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/30 16:25
     */
    public function del()
    {
        $params = (new CourseColumnValidate())->post()->goCheck('del');
        (new CourseColumnLogic())->del($params['id']);
        return $this->success('删除成功',[],1,1);

    }
}