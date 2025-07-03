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
namespace app\common\model\order;
use app\common\enum\OrderEnum;
use app\common\model\BaseModel;
use app\common\model\course\CourseCommentImage;
use app\common\service\FileService;

/**
 * 订单课程模型类
 * Class OrderCourse
 * @package app\common\model\order
 */
class OrderCourse extends BaseModel
{

    //json转数组
    protected $json = ['course_snap'];
    protected $jsonAssoc = true;


    public function commentImage()
    {
        return $this->hasMany(CourseCommentImage::class,'id','commend_id');
    }


    public function getCourseSnapAttr($value,$data)
    {
        if (isset($data['order_id'])) {
            $order = Order::where(['id'=>$data['order_id']])->findOrEmpty()->toArray();
            if ($order['type'] == OrderEnum::ORDER_TYPE_QUESTIONBANK) {
                $value['cover'] = FileService::getFileUrl('resource/image/adminapi/default/questionbank_order.png');
                $value['sell_price'] = $value['pay_amount'];
            }
        }
        return $value;
    }

}