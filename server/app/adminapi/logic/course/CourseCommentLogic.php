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
use app\common\model\course\CourseComment;
use think\Exception;

/**
 * 课程评论逻辑类
 * Class CourseCommentLogic
 * @package app\adminapi\logic\course
 */
class CourseCommentLogic
{

    /**
     * @notes 评论内容
     * @param array $params
     * @return bool|string
     * @author cjhao
     * @date 2022/6/20 18:02
     */
    public function reply(array $params)
    {
        try{
            $courseComment = CourseComment::findOrEmpty($params['id']);
            if($courseComment->isEmpty()){
                throw new Exception('评论不存在');
            }
//            if($courseComment->reply){
//                throw new Exception('该评论已回复');
//            }
            $courseComment->reply = $params['content'];
            $courseComment->save();
            return true;
        }catch (Exception $e){
            return $e->getMessage();
        }

    }

    /**
     * @notes 删除评论
     * @param array $id
     * @return bool
     * @author cjhao
     * @date 2022/6/20 18:08
     */
    public function del(int $id)
    {
        CourseComment::destroy($id);
        return true;
    }
}