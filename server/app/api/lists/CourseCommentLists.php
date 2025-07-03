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
namespace app\api\lists;
use app\common\enum\OrderEnum;
use app\common\enum\OrderRefundEnum;
use app\common\enum\RefundEnum;
use app\common\lists\BaseDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\course\CourseComment;
use app\common\model\course\CourseCommentImage;
use app\common\model\order\OrderCourse;
use app\common\service\FileService;

/**
 * 课程评论列表类
 * Class CourseCommentLists
 * @package app\api\lists
 */
class CourseCommentLists extends BaseApiDataLists implements ListsSearchInterface
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = OrderCourse::alias('OC')
            ->join('order O', 'O.id = OC.order_id')
            ->leftjoin('CourseComment CC','CC.order_id = O.id')
            ->where(['O.user_id' => $this->userId,'order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'refund_status'=>OrderEnum::REFUND_STATUS_NOT])
            ->where($this->searchWhere)
            ->order('O.id desc')
            ->field('OC.id,CC.id as comment_id,O.sn,OC.course_snap,CC.course_score,CC.comment,CC.reply,CC.create_time,OC.order_id')
            ->limit($this->limitOffset, $this->limitLength)
            ->select();

        $commentIds = array_column($lists->toarray(),'comment_id');
        $commentImageLists = [];
        if($commentIds){
            $commentImageLists = CourseCommentImage::where(['comment_id'=>$commentIds])->column('comment_id,uri');
        }
        foreach ($lists as $comment){
            $commentImage = [];
            foreach ($commentImageLists as $image)
            {
                if($comment->comment_id == $image['comment_id']){
                    $commentImage[] = FileService::getFileUrl($image['uri']);
                }
            }
            $comment->comment_image = $commentImage;
            $comment->create_time = date('Y-m-d H:i:s',$comment->create_time);
        }

        return $lists->toarray();
    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        $count =  OrderCourse::alias('OC')
            ->join('order O', 'O.id = OC.order_id')
            ->leftjoin('CourseComment CC','CC.order_id = O.id')
            ->where(['O.user_id' => $this->userId,'order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE])
            ->where($this->searchWhere)
            ->count();

        return $count;
    }

    /**
     * @notes 设置搜索条件
     * @return array
     * @author 令狐冲
     * @date 2021/7/7 19:44
     */
    public function setSearch(): array
    {
        return ['='=>['is_comment']];
        
    }
}