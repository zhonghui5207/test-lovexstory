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
namespace app\api\validate;
use app\common\validate\BaseValidate;

/**
 * 课程评论验证类
 * Class CourseCommentValidate
 * @package app\api\validate
 */
class CourseCommentValidate extends BaseValidate
{

    protected $rule = [
        'id'        => 'require',
        'score'     => 'require|between:1,5',
//        'comment'   => 'lenght:255',
//        'image'     => 'array|lenght:8',
    ];
    protected $message = [
        'id.require'        => '请选择评价订单',
        'score.require'     => '请选择评分',
        'comment.lenght'    => '评论内容不能超过255字符',
        'image.array'       => '评论图片数据错误',
        'image.lenght'      => '评论图不能超过8张'
    ];

    protected function sceneId()
    {
        return $this->only(['id']);
    }
}