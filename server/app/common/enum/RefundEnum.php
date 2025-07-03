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
namespace app\common\enum;
/**
 * 退款枚举
 * Class RefundEnum
 * @package app\common\enum
 */
class RefundEnum
{
    const REFUND_STATUS_ING         = 1;
    const REFUND_STATUS_FAIL        = 2;
    const REFUND_STATUS_SUCCESS     = 3;

    /**
     * @notes 退款状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/22 16:08
     */
    protected function getRefundStatusDesc($from = true)
    {
        $desc = [
            self::REFUND_STATUS_ING             => '退款中',
            self::REFUND_STATUS_FAIL            => '退款失败',
            self::REFUND_STATUS_SUCCESS         => '退款成功',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';

    }

}