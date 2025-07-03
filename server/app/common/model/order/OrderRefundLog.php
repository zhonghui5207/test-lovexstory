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


use app\common\enum\OrderRefundEnum;
use app\common\model\auth\Admin;
use app\common\model\BaseModel;
use app\common\model\user\User;

class OrderRefundLog extends BaseModel
{
    /**
     * @notes 退款状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2022/9/9 4:59 下午
     */
    public function getRefundStatusDescAttr($value,$data)
    {
        return OrderRefundEnum::getStatusDesc($data['refund_status']);
    }

    /**
     * @notes 操作人
     * @param $value
     * @param $data
     * @return mixed|string
     * @author ljj
     * @date 2022/9/9 5:59 下午
     */
    public function getOperatorDescAttr($value,$data)
    {
        switch ($data['type']) {
            case 1:
                return '系统';
            case 2:
                return Admin::where('id',$data['operator_id'])->value('name');
            case 3:
                return User::where('id',$data['operator_id'])->value('nickname');
            default:
                return '';
        }
    }
}