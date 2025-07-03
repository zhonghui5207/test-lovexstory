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
namespace app\api\controller;
use app\api\logic\CourseCommentLogic;
use app\api\validate\CourseCommentValidate;

/**
 * 课程评论表
 * Class CourseComment
 * @package app\api\controller
 */
class CourseCommentController extends BaseApiController
{

    /**
     * @notes 获取待评论和已评论数量
     * @return array
     * @author cjhao
     * @date 2022/6/16 17:03
     */
    public function commentCount()
    {
        $data = (new CourseCommentLogic())->commentCount($this->userId);
        return $this->success('',$data);

    }

    /**
     * @notes 评论列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/16 17:42
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 课程评论
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/16 18:04
     */
    public function comment()
    {
        $params = (new CourseCommentValidate())->post()->goCheck(null);
        $params['user_id'] = $this->userId;
        $result = (new CourseCommentLogic())->comment($params);
        if(true === $result){
            return $this->success('评论成功');
        }
        return $this->fail($result);
    }

    /**
     * @notes 获取评论商品信息
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/21 16:32
     */
    public function commentGoodsInfo()
    {
        $params = (new CourseCommentValidate())->goCheck('id');
        $params['user_id'] = $this->userId;
        $detail = (new CourseCommentLogic())->commentGoodsInfo($params);
        return $this->success('',$detail);
    }


}