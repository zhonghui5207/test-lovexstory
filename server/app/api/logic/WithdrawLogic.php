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

namespace app\api\logic;

use app\common\enum\AccountLogEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\enum\WithdrawEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\model\user\User;
use app\common\model\WithdrawApply;
use app\common\service\ConfigService;
use think\facade\Db;


class WithdrawLogic extends BaseLogic
{
    /**
     * @notes 获取提现配置
     * @param $userId
     * @param $terminal
     * @return array
     * @author ljj
     * @date 2022/12/16 16:45
     */
    public static function getConfig($userId,$terminal)
    {
        $user = User::findOrEmpty($userId)->toArray();
        $config = [
            'user_earnings' => $user['user_earnings'],
            'min_withdraw' => ConfigService::get('config', 'withdraw_min_money', WithdrawEnum::DEFAULT_MIN_MONEY),
            'max_withdraw' => ConfigService::get('config', 'withdraw_max_money', WithdrawEnum::DEFAULT_MAX_MONEY),
            'percentage' => ConfigService::get('config', 'withdraw_service_charge', WithdrawEnum::DEFAULT_PERCENTAGE),
        ];

        $types = ConfigService::get('config', 'withdraw_way',[1]);
        foreach($types as $value) {
            //h5隐藏微信零钱提现
            if(in_array($terminal,[UserTerminalEnum::H5]) && WithdrawEnum::TYPE_WECHAT_CHANGE == $value){
                continue;
            }
            $config['type'][] = [
                'name' => WithdrawEnum::getTypeDesc($value),
                'value' => $value
            ];
        }
        return $config;
    }

    /**
     * @notes 提现申请
     * @param $params
     * @return false
     * @author ljj
     * @date 2022/12/16 16:59
     */
    public static function apply($params)
    {
        Db::startTrans();
        try {
            // 手续费,单位：元
            $handlingFee = 0;
            if($params['type'] != WithdrawEnum::TYPE_BALANCE) {
                // 不是提现至余额，需收手续费
                $percentage = ConfigService::get('config', 'withdraw_service_charge', WithdrawEnum::DEFAULT_PERCENTAGE);
                $handlingFee = round(($params['money'] * $percentage / 100), 2);
            }

            $withdrawApply = new WithdrawApply();
            $data = [
                'sn' => generate_sn($withdrawApply, 'sn'),
                'user_id' => $params['user_id'],
                'real_name' => $params['real_name'] ?? '',
                'account' => $params['account'] ?? '',
                'type' => $params['type'],
                'money' => $params['money'],
                'left_money' => $params['money'] - $handlingFee,
                'money_qr_code' => $params['money_qr_code'] ?? '',
                'handling_fee' => $handlingFee,
                'apply_remark' => $params['apply_remark'] ?? '',
                'bank' => $params['bank'] ?? '',
                'subbank' => $params['subbank'] ?? '',
                'batch_no' => generate_sn($withdrawApply, 'batch_no','SJZZ'),
            ];
            // 新增提现申请记录
            $withdrawApply->save($data);

            // 扣减用户可提现金额
            $user = User::find($params['user_id']);
            $user->user_earnings = $user->user_earnings - $params['money'];
            $user->save();

            //流水记录
            AccountLogLogic::record($params['user_id'], AccountLogEnum::EAR,AccountLogEnum::EAR_DEC_WITHDRAW,AccountLogEnum::DEC, $params['money'], $withdrawApply->sn);

            Db::commit();
            return $withdrawApply->id;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 提现申请详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2022/12/16 17:05
     */
    public static function detail($params)
    {
        $result = WithdrawApply::field('id,sn,type,money,left_money,handling_fee,real_name,account,money_qr_code,apply_remark,status,transfer_voucher,transfer_remark,bank,subbank,verify_remark,verify_time,create_time')
            ->append(['status_desc','type_desc'])
            ->findOrEmpty($params['id'])
            ->toArray();

        return $result;
    }
}