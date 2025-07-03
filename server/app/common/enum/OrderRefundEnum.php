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


class OrderRefundEnum
{
    //操作人类型
    const TYPE_SYSTEM   = 1;//系统
    const TYPE_ADMIN    = 2;//后台
    const TYPE_USER     = 3;//用户

    //退款状态
    const STATUS_ING        = 0;//退款中
    const STATUS_SUCCESS    = 1;//退款成功
    const STATUS_FAIL       = 2;//退款失败

    const REFUND_TYPE_PLATFORM_REFUND   = 1;


    /**
     * @notes 获取退款类型
     * @param bool $from
     * @return array
     * @author cjhao
     * @date 2022/10/31 18:29
     */
    public static function getTypeDesc($from = true)
    {
        $desc = [
            self::REFUND_TYPE_PLATFORM_REFUND       => '后台退款',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? "";
    }

    /**
     * @notes 操作人
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2022/2/11 2:17 下午
     */
    public static function getOperatorDesc($value = true)
    {
        $desc = [
            self::TYPE_SYSTEM           => '系统',
            self::TYPE_ADMIN            => '后台',
            self::TYPE_USER             => '用户',
        ];

        if (true === $value) {
            return $desc;
        }
        return $desc[$value];
    }


    /**
     * @notes 退款状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2022/9/8 6:45 下午
     */
    public static function getStatusDesc($value = true)
    {
        $desc = [
            self::STATUS_ING                => '退款中',
            self::STATUS_SUCCESS            => '退款成功',
            self::STATUS_FAIL               => '退款失败',
        ];

        if (true === $value) {
            return $desc;
        }
        return $desc[$value];
    }
}