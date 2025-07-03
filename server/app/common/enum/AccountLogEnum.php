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
 * 会员账户流水变动表枚举
 * Class AccountLogEnum
 * @package app\common\enum
 */
class AccountLogEnum
{
    //变动对象
    const BNW = 1;//可用余额
    const EAR = 2;//可提现佣金

    //动作
    const DEC = 0;//减少
    const INC = 1;//增加


    //用户余额
    const BNW_INC_ADMIN         = 200;
    const BNW_DEC_ADMIN         = 201;
    const BNW_DEC_ORDER         = 202;
    const BNW_INC_RECHARGE      = 203;
    const BNW_INC_RECHARGE_GIVE = 204;
    const BNW_INC_REFUND        = 205;
    const BNW_INC_WITHDRAW        = 206;

    //余额变动
    const BNW_COLLECT = [
        self::BNW_DEC_ADMIN,
        self::BNW_INC_ADMIN,
        self::BNW_DEC_ORDER,
        self::BNW_INC_RECHARGE,
        self::BNW_INC_RECHARGE_GIVE,
        self::BNW_INC_REFUND,
        self::BNW_INC_WITHDRAW,
    ];


    //可提现佣金
    const EAR_INC_ADMIN         = 300;//管理员增加可提现佣金
    const EAR_DEC_ADMIN         = 301;//管理员减少可提现佣金
    const EAR_INC_SETTLEMENT    = 302;//佣金结算
    const EAR_DEC_WITHDRAW      = 303;//佣金提现
    const EAR_INC_WITHDRAW_FAIL = 304;//佣金提现失败

    //佣金变动
    const EAR_COLLECT = [
        self::EAR_INC_ADMIN,
        self::EAR_DEC_ADMIN,
        self::EAR_INC_SETTLEMENT,
        self::EAR_DEC_WITHDRAW,
        self::EAR_INC_WITHDRAW_FAIL,
    ];


    /**
     * @notes 获取动作
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/5/24 17:44
     */
    public static function getActionDesc($from = true)
    {
        $desc = [
            self::DEC => '减少',
            self::INC => '增加',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取变动类型
     * @param $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/5/24 17:40
     */
    public static function getChangeTypeDesc($from = true)
    {
        $desc = [
            self::BNW_INC_ADMIN         => '管理员减少余额',
            self::BNW_DEC_ADMIN         => '管理员增加余额',
            self::BNW_DEC_ORDER         => '购买课程',
            self::BNW_INC_RECHARGE      => '充值余额',
            self::BNW_INC_RECHARGE_GIVE => '充值赠送余额',
            self::BNW_INC_REFUND        => '订单退款',
            self::EAR_INC_ADMIN         => '管理员增加可提现佣金',
            self::EAR_DEC_ADMIN         => '管理员减少可提现佣金',
            self::EAR_INC_SETTLEMENT    => '佣金结算',
            self::EAR_DEC_WITHDRAW      => '佣金提现',
            self::EAR_INC_WITHDRAW_FAIL => '佣金提现失败',
            self::BNW_INC_WITHDRAW        => '佣金提现',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }


    /**
     * @notes 获取可用余额类型描述
     * @return string|string[]
     * @author ljj
     * @date 2022/12/2 5:42 下午
     */
    public static function getMoneyChangeTypeDesc()
    {
        $change_type = self::BNW_COLLECT;
        $change_type_desc = self::getChangeTypeDesc(true);
        $change_type_desc = array_filter($change_type_desc, function($key)  use ($change_type) {
            return in_array($key, $change_type);
        }, ARRAY_FILTER_USE_KEY);
        return $change_type_desc;
    }

    /**
     * @notes 获取可提现余额类型描述
     * @return string|string[]
     * @author ljj
     * @date 2022/12/2 5:42 下午
     */
    public static function getEarningsChangeTypeDesc()
    {
        $change_type = self::EAR_COLLECT;
        $change_type_desc = self::getChangeTypeDesc(true);
        $change_type_desc = array_filter($change_type_desc, function($key)  use ($change_type) {
            return in_array($key, $change_type);
        }, ARRAY_FILTER_USE_KEY);
        return $change_type_desc;
    }


}
