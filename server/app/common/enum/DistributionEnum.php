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

namespace app\common\enum;


class DistributionEnum
{
    //分销申请审核状态
    const STATUS_WAIT = 0;// 等待审核
    const STATUS_PASS = 1;// 审核通过
    const STATUS_REFUSE = 2;// 审核拒绝

    //分销订单佣金状态commission
    const COMMISSION_STATUS_WAIT = 1;// 待结算
    const COMMISSION_STATUS_SUCCESS = 2;// 已结算
    const COMMISSION_STATUS_FAIL = 3;// 已失效

    //分销订单分佣层级
    const COMMISSION_LEVEL_SELF = 1;// 自购分佣
    const COMMISSION_LEVEL_ONE = 2;// 一级分佣
    const COMMISSION_LEVEL_TWO = 3;// 二级分佣

    //分销等级升级条件
    const UPGRADE_CONDITIONS_ANY = 1;// 满足任意条件
    const UPGRADE_CONDITIONS_ALL = 2;// 满足全部条件


    /**
     * @notes 审核状态
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2023/10/23 12:09 下午
     */
    public static function getStatusDesc($from = true)
    {
        $desc = [
            self::STATUS_WAIT => '待审核',
            self::STATUS_PASS => '审核通过',
            self::STATUS_REFUSE => '审核拒绝',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 佣金状态
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2023/10/25 11:41 上午
     */
    public static function getCommissionStatusDesc($from = true)
    {
        $desc = [
            self::COMMISSION_STATUS_WAIT => '待结算',
            self::COMMISSION_STATUS_SUCCESS => '已结算',
            self::COMMISSION_STATUS_FAIL => '已失效',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 分佣层级
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2023/10/25 12:07 下午
     */
    public static function getCommissionLevelDesc($from = true)
    {
        $desc = [
            self::COMMISSION_LEVEL_SELF => '自购分佣',
            self::COMMISSION_LEVEL_ONE => '一级分佣',
            self::COMMISSION_LEVEL_TWO => '二级分佣',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }


    /**
     * @notes 分销等级升级条件
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2023/10/26 10:44 上午
     */
    public static function getUpgradeConditionsDesc($from = true)
    {
        $desc = [
            self::UPGRADE_CONDITIONS_ANY => '满足任意条件',
            self::UPGRADE_CONDITIONS_ALL => '满足全部条件',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }
}
