<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\common\enum\marketing;
/**
 * 用户优惠券枚举类
 * Class CouponEnum
 * @package app\common\enum\martketing
 */
class CouponListEnum
{

    const STATUS_NOT_USE    = 1;
    const STATUS_USE        = 2;
    const STATUS_OVERTIME   = 3;
    const STATUS_CANCEL     = 4;


    const CHANNEL_USER      = 1;
    const CHANNEL_SYSTEM    = 2;

    /**
     * @notes 获取用户优惠券状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/12 18:24
     */
    public static function getStatusDesc($from = true){
        $desc = [
            self::STATUS_NOT_USE    => '未使用',
            self::STATUS_USE        => '已使用',
            self::STATUS_OVERTIME   => '已过期',
            self::STATUS_CANCEL     => '已作废',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }


    /**
     * @notes 获取领取渠道
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/13 10:50
     */
    public static function getChannelDesc($from = true)
    {
        $desc = [
            self::CHANNEL_USER      => '用户领取',
            self::CHANNEL_SYSTEM    => '系统赠送',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

}