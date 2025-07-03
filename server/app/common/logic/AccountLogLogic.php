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
namespace app\common\logic;
use app\common\enum\AccountLogEnum;
use app\common\model\AccountLog;
use app\common\model\user\User;

/**
 * 用户账户变动逻辑类
 * Class AccountLogLogic
 * @package app\common\logic
 *
 */
class AccountLogLogic
{
    /**
     * @notes 添加账户流水记录
     * @param $userId //会员ID
     * @param $changeObject //变动对象
     * @param $changeType //变动类型
     * @param $action //变动动作
     * @param $changeAmount //变动数量
     * @param string $associationSn //关联单号
     * @param string $remark //备注
     * @param array $feature //预留字段，方便存更多其它信息
     * @return bool
     * @author ljj
     * @date 2022/10/28 5:15 下午
     */
    public static function record($userId, $changeObject, $changeType, $action, $changeAmount, $associationSn = '', $remark = '', $feature = [])
    {
        $user = User::findOrEmpty($userId);
        if($user->isEmpty()) {
            return false;
        }

        $left_amount = 0;
        switch ($changeObject) {
            case AccountLogEnum::BNW:
                $left_amount = $user->user_money;
                break;
            case AccountLogEnum::EAR:
                $left_amount = $user->user_earnings;
                break;
        }

        $accountLog = new AccountLog();
        $data = [
            'sn' => generate_sn($accountLog, 'sn', 20),
            'user_id' => $userId,
            'change_object' => $changeObject,
            'change_type' => $changeType,
            'action' => $action,
            'left_amount' => $left_amount,
            'change_amount' => $changeAmount,
            'association_sn' => $associationSn,
            'remark' => $remark,
            'feature' => $feature ? json_encode($feature, JSON_UNESCAPED_UNICODE) : '',
        ];
        return $accountLog->save($data);
    }

}