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
 * 优惠券枚举类
 * Class CouponEnum
 * @package app\common\enum\martketing
 */
class CouponEnum
{

    //使用时间
    const USE_TIME_TYPE_FIXED       = 1;        //使用时间：固定日期
    const USE_TYPE_TYPE_TODAY       = 2;        //使用时间：当日起
    const USE_TYPE_TYPE_MORROW      = 3;        //使用时间：次日起


    //领取方式
    const GET_TYPE_USER             = 1;         //领取方式：用户领取
    const GET_TYPE_SYSTEM           = 2;         //领取方式：系统赠送


    //使用门槛
    const USE_CONDITION_NO          = 1;        //使用门槛：无门槛
    const USE_CONDITION_MONEY       = 2;        //使用门槛：订单满X金额可用


    //领取次数
    const GET_NUM_TYPE_NO               = 1;        //领取次数：不限领取
    const GET_NUM_TYPE_LIMIT            = 2;        //领取次数：限制领取
    const GET_NUM_TYPE_LIMIT_TODAY      = 3;        //领取次数：每天限制领取


    //使用范围
    const USER_SCOPE_NO                 = 1;        //全场通用
    const USER_SCOPE_COUSE_USABLE       = 2;        //指定课程可用
    const USER_SCOPE_COUSE_NUSABLE      = 3;        //指定课程不可用

    //优惠券状态
    const STATUS_NOT_START              = 1;        //未开始
    const STATUS_ING                    = 2;        //进行中
    const STATUS_END                    = 3;        //结束

    // 使用状态
    const USE_STATUS_NOT    = 1; //未使用
    const USE_STATUS_OK     = 2; //已使用
    const USE_STATUS_EXPIRE = 3; //已过期
    const USE_STATUS_VOID   = 4; //已作废

    // 发放数量限制
    const SEND_NUM_TYPE_NOT   = 1; // 无限数量
    const SEND_NUM_TYPE_FIXED = 2; // 固定数量


    /**
     * @notes 获取使用时间
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/11 11:55
     */
    public static function getUseTypeDesc($from = true)
    {
        $desc = [
            self::USE_TIME_TYPE_FIXED   => '固定日期',
            self::USE_TYPE_TYPE_TODAY   => '领取当日起',
            self::USE_TYPE_TYPE_MORROW  => '领取次日起',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }



    /**
     * @notes 获取领取方式
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/11 11:57
     */
    public static function getGetTypeDesc($from = true)
    {
        $desc = [
            self::GET_TYPE_USER         => '用户领取',
            self::GET_TYPE_SYSTEM       => '系统赠送',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';

    }


    /**
     * @notes 获取使用门槛
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/11 11:57
     */
    public static function getUseConditionDesc($from = true)
    {
        $desc = [
            self::USE_CONDITION_NO          => '无门槛',
            self::USE_CONDITION_MONEY       => '订单满金额可用',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';

    }



    /**
     * @notes 获取使用范围
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/11 11:59
     */
    public static function getUseScopeDesc($from = true)
    {
        $desc = [
            self::USER_SCOPE_NO          => '全场可通用',
            self::USER_SCOPE_COUSE_USABLE       => '指定课程可用',
            self::USER_SCOPE_COUSE_NUSABLE => '指定课程不可用',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';

    }


    /**
     * @notes 获取状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2023/4/12 15:33
     */
    public static function getStatusDesc($from = true)
    {
        $desc = [
            self::STATUS_NOT_START      => '未开始',
            self::STATUS_ING            => '进行中',
            self::STATUS_END            => '已结束'
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';

    }

    /**
     * @notes 优惠券使用状态
     * @param bool $value
     * @return array|mixed
     * @author 张无忌
     * @date 2021/7/21 17:03
     */
    public static function getUseStatusDesc($value = true)
    {
        $data = [
            self::USE_STATUS_NOT     => '未使用',
            self::USE_STATUS_OK      => '已使用',
            self::USE_STATUS_EXPIRE  => '已过期',
            self::USE_STATUS_VOID    => '已作废'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }
}