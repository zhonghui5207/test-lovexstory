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
namespace app\adminapi\lists\order;
use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\course\CourseComment;
use app\common\model\order\OrderCourse;
use app\common\service\FileService;
use think\facade\Db;

/**
 * 订单评论列表类
 * Class OrderCommentLists
 * @package app\adminapi\lists\order
 */
class CourseCommentLists extends BaseAdminDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = CourseComment::alias('CC')
                ->with(['comment_image'])
                ->join('order_course OC','CC.order_id = OC.order_id')
                ->join('user U','CC.user_id = U.id')
                ->where($this->setSearch())
                ->limit($this->limitOffset, $this->limitLength)
                ->field('CC.id,CC.user_id,CC.order_id,CC.course_score,CC.comment,CC.reply,nickname,avatar,sn,CC.create_time')
                ->order('CC.id desc')
                ->select();
        $orderIds = array_column($lists->toArray(),'order_id');
        $orderCourse = OrderCourse::where(['order_id'=>$orderIds])->column('course_snap,id','order_id');
        foreach ($lists as $comment){
            $comment->avatar = FileService::getFileUrl($comment->avatar);
            $comment->status = 0;
            if($comment->reply){
                $comment->status = 1;
            }
            $comment->course_snap = $orderCourse[$comment['order_id']]['course_snap'] ?? [];


        }
        return $lists->toArray();

    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {
        $count =  CourseComment::alias('CC')
            ->join('order_course OC','CC.order_id = OC.order_id')
            ->join('user U','CC.user_id = U.id')
            ->where($this->setSearch())
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
        $where = [];
        if(isset($this->params['name']) && $this->params['name']){
            $where[] = ['OC.course_snap->name','like','%'.$this->params['name'].'%'];
        }
        if(isset($this->params['keyword']) && $this->params['keyword']){
            $where[] = ['U.nickname|U.mobile','like','%'.$this->params['keyword'].'%'];
        }
        if(isset($this->params['status']) && '' != $this->params['status']){
            $wheresql = Db::raw('is null');
            $this->params['status'] > 0 && $wheresql = Db::raw('is not null');
            $where[] = ['CC.reply','exp',$wheresql];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['CC.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['CC.create_time','<=',strtotime($this->params['end_time'])];
        }
        if(isset($this->params['score']) && $this->params['score']){
            switch ($this->params['score']){
                case 1://好评
                    $where[]= ['CC.course_score','>',3];
                    break;
                case 2://中评
                    $where[]= ['CC.course_score','=',3];
                    break;
                case 3://差评
                    $where[]= ['CC.course_score','<',3];
                    break;
            }

        }
        return $where;
    }
}