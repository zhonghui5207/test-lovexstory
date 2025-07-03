<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\enum;


class WithdrawEnum
{
    //默认值
    const DEFAULT_MIN_MONEY = 0;
    const DEFAULT_MAX_MONEY = 100;
    const DEFAULT_PERCENTAGE = 10;
    const DEFAULT_TYPE = [self::TYPE_BALANCE];

    //类型
    const TYPE_BALANCE = 1;         //余额
    const TYPE_WECHAT_CHANGE = 2;   //微信零钱
    const TYPE_BANK = 3;            //银行卡
    const TYPE_WECHAT_CODE = 4;     //微信收款码
    const TYPE_ALI_CODE = 5;        //支付宝收款码

    //状态
    const STATUS_WAIT = 1;//待提现
    const STATUS_ING = 2;//提现中
    const STATUS_SUCCESS = 3;//提现成功
    const STATUS_FAIL = 4;//提现失败

    //微信零钱提现方式
    const MERCHANT = 1;//商家转账到零钱

    /**
     * @notes 类型
     * @param $type
     * @param false $flag
     * @return string|string[]
     * @author ljj
     * @date 2022/12/2 5:14 下午
     */
    public static function getTypeDesc($type, $flag = false)
    {
        $desc = [
            self::TYPE_BALANCE => '钱包余额',
            self::TYPE_WECHAT_CHANGE => '微信零钱',
            self::TYPE_BANK => '银行卡',
            self::TYPE_WECHAT_CODE => '微信收款码',
            self::TYPE_ALI_CODE => '支付宝收款码',
        ];
        if($flag) {
            return $desc;
        }
        return $desc[$type] ?? '';
    }

    /**
     * @notes 状态
     * @param $status
     * @param false $flag
     * @return string|string[]
     * @author ljj
     * @date 2022/12/2 5:14 下午
     */
    public static function getStatusDesc($status, $flag = false)
    {
        $desc = [
            self::STATUS_WAIT => '待提现',
            self::STATUS_ING => '提现中',
            self::STATUS_SUCCESS => '提现成功',
            self::STATUS_FAIL => '提现失败',
        ];
        if($flag) {
            return $desc;
        }
        return $desc[$status] ?? '';
    }
}
