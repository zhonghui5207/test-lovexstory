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
namespace app\adminapi\controller\order;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\course\CourseCommentLogic;
use app\adminapi\validate\order\CourseCommentValidate;

/**
 * 课程评论类
 * Class CourseCommentController
 * @package app\adminapi\controller\order
 */
class CourseCommentController extends BaseAdminController
{

    /**
     * @notes 评论列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/20 17:32
     */
    public function lists()
    {
        return $this->dataLists();
    }


    /**
     * @notes 回复评论
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/20 18:02
     */
    public function reply()
    {
        $params = (new CourseCommentValidate())->post()->goCheck();
        $result = (new CourseCommentLogic())->reply($params);
        if(true === $result){
            return $this->success('回复成功');
        }
        return $this->fail($result);
    }


    /**
     * @notes 删除评论
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/20 18:05
     */
    public function del()
    {

        $params = (new CourseCommentValidate())->post()->goCheck('del');
        (new CourseCommentLogic())->del($params['id']);
        return $this->success('删除成功');
    }

}