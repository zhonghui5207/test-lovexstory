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
namespace app\common\model\course;
use app\common\model\BaseModel;
use app\common\model\order\OrderCourse;
use app\common\model\user\User;
use think\model\concern\SoftDelete;

/**
 * 课程评价模型类
 * Class CourseComment
 * @package app\common\model\course
 */
class CourseComment extends BaseModel
{

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function nickname()
    {
        return $this->hasOne(User::class,'id','user_id')
                ->bind(['nickname','avatar']);
    }

    public function commentImage()
    {
        return $this->hasMany(CourseCommentImage::class,'comment_id');
    }

    public function orderCourse()
    {
        return $this->hasOne(OrderCourse::class,'order_id','order_id')
            ->bind(['course_snap']);
    }


}