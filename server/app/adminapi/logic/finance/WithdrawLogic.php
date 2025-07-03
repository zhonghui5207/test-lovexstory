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

namespace app\adminapi\logic\finance;

use app\common\enum\AccountLogEnum;
use app\common\enum\WithdrawEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\model\WithdrawApply;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\wechat\WeChatConfigService;
use think\facade\Db;


class WithdrawLogic extends BaseLogic
{
    /**
     * @notes 提现详情
     * @param $params
     * @return mixed
     * @author ljj
     * @date 2022/12/6 3:08 下午
     */
    public static function detail($params)
    {
        $result = WithdrawApply::field('id,user_id,money,handling_fee,left_money,type,create_time,status,transfer_voucher,transfer_time,transfer_remark,account,real_name,bank,subbank,money_qr_code,apply_remark,verify_remark,transfer_remark,verify_time')
            ->with(['user' => function($query){
                $query->field('id,sn,nickname,mobile,avatar');
            }])
            ->append(['type_desc','status_desc'])
            ->findOrEmpty($params['id'])
            ->toArray();

        return $result;
    }

    /**
     * @notes 审核拒绝
     * @param $params
     * @return bool
     * @author ljj
     * @date 2022/12/6 3:41 下午
     */
    public static function refuse($params)
    {
        Db::startTrans();
        try {
            // 修改提现申请单状态
            $withdrawApply = WithdrawApply::findOrEmpty($params['id']);
            $withdrawApply->status = WithdrawEnum::STATUS_FAIL;
            $withdrawApply->verify_remark = $params['verify_remark'];
            $withdrawApply->verify_time = time();
            $withdrawApply->save();

            // 回退提现金额
            $user = User::findOrEmpty($withdrawApply->user_id);
            $user->user_earnings = $user->user_earnings + $withdrawApply->money;
            $user->save();

            // 增加账户流水变动记录
            AccountLogLogic::record($user->id,AccountLogEnum::EAR,AccountLogEnum::EAR_INC_WITHDRAW_FAIL,AccountLogEnum::INC,$withdrawApply['money'], $withdrawApply['sn']);

            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 审核通过
     * @param $params
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author ljj
     * @date 2022/12/6 3:56 下午
     */
    public static function pass($params)
    {
        Db::startTrans();
        try {
            $withdraw_apply = WithdrawApply::findOrEmpty($params['id'])->toArray();

            switch($withdraw_apply['type']) {
                // 提现至余额
                case WithdrawEnum::TYPE_BALANCE:
                    // 增加用户余额
                    User::update(['user_money'=>['inc',$withdraw_apply['left_money']]],['id'=>$withdraw_apply['user_id']]);
                    // 更新提现状态
                    WithdrawApply::update(['status'=>WithdrawEnum::STATUS_SUCCESS,'verify_remark'=>($params['verify_remark'] ?? ''),'verify_time'=>time()],['id'=>$withdraw_apply['id']]);
                    // 记录账户流水
                    AccountLogLogic::record($withdraw_apply['user_id'],AccountLogEnum::BNW,AccountLogEnum::BNW_INC_WITHDRAW,AccountLogEnum::INC,$withdraw_apply['left_money'], $withdraw_apply['sn']);
                    break;
                // 提现至微信零钱
                case WithdrawEnum::TYPE_WECHAT_CHANGE:
                    // 用户授权信息
                    $userAuth = UserAuth::where('user_id', $withdraw_apply['user_id'])->order('terminal', 'asc')->findOrEmpty();
                    // 获取app
                    $config = WeChatConfigService::getWechatConfigByTerminal($userAuth->terminal);

                    //微信零钱接口：1-商家转账到零钱;
                    $transfer_way = ConfigService::get('config', 'transfer_way',1);
                    //商家转账到零钱
                    if ($transfer_way == WithdrawEnum::MERCHANT) {
                        WechatMerchantTransferLogic::transfer($withdraw_apply,$userAuth,$config);
                    }
                    break;
                // 提现至银行卡
                case WithdrawEnum::TYPE_BANK:
                // 提现至微信收款码
                case WithdrawEnum::TYPE_WECHAT_CODE:
                // 提现至支付宝收款码
                case WithdrawEnum::TYPE_ALI_CODE:
                    WithdrawApply::update(['status'=>WithdrawEnum::STATUS_ING,'verify_remark'=>($params['verify_remark'] ?? ''),'verify_time'=>time()],['id'=>$withdraw_apply['id']]);
                    break;
            }

            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 查询(提现至微信零钱)
     * @param $params
     * @return false|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author ljj
     * @date 2022/12/6 4:37 下午
     */
    public static function search($params)
    {
        Db::startTrans();
        try  {
            $withdrawApply = WithdrawApply::findOrEmpty($params['id']);
            // 用户授权信息
            $userAuth = UserAuth::where('user_id', $withdrawApply->user_id)->order('terminal', 'asc')->findOrEmpty();
            if($userAuth->isEmpty()) {
                throw new \think\Exception('获取不到用户的openid');
            }
            // 获取app
            $config = WeChatConfigService::getWechatConfigByTerminal($userAuth->terminal);

            //微信零钱接口：1-商家转账到零钱;
            $transfer_way = ConfigService::get('config', 'transfer_way',1);
            //商家转账到零钱
            if ($transfer_way == WithdrawEnum::MERCHANT) {
                $result = WechatMerchantTransferLogic::details($withdrawApply,$config);
                $tips =  '未知';
                if(isset($result['detail_status'])) {
                    if ($result['detail_status'] == 'SUCCESS') {
                        //提现成功，更新提现申请单
                        WithdrawApply::update([
                            'status' => WithdrawEnum::STATUS_SUCCESS,
                            'pay_search_result' => json_encode($result, JSON_UNESCAPED_UNICODE),
                            'payment_no' => $result['detail_id'],
                            'payment_time' => strtotime($result['update_time']),
                        ],['id'=>$withdrawApply['id']]);
                        $tips = '提现成功';
                    }
                    if ($result['detail_status'] == 'FAIL') {
                        //提现失败，更新提现申请单
                        WithdrawApply::update([
                            'status' => WithdrawEnum::STATUS_FAIL,
                            'pay_search_result' => json_encode($result, JSON_UNESCAPED_UNICODE),
                        ],['id'=>$withdrawApply['id']]);

                        // 回退提现金额
                        $user = User::findOrEmpty($withdrawApply->user_id);
                        $user->user_earnings = $user->user_earnings + $withdrawApply->money;
                        $user->save();
                        // 记录账户流水
                        AccountLogLogic::record($withdrawApply->user_id,AccountLogEnum::EAR,AccountLogEnum::EAR_INC_WITHDRAW_FAIL,AccountLogEnum::INC,$withdrawApply->money, $withdrawApply->sn);
                        $tips =  '提现失败';
                    }
                    if ($result['detail_status'] == 'PROCESSING') {
                        $tips =  '正在处理中';
                    }
                }else {
                    // 查询失败
                    throw new \think\Exception($result['message'] ?? '商家转账到零钱查询失败');
                }
            }

            // 提交事务
            Db::commit();
            // 返回提示消息
            return $tips;
        } catch(\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 转账成功
     * @param $params
     * @return bool
     * @author ljj
     * @date 2022/12/6 4:50 下午
     */
    public static function transferSuccess($params)
    {
        $params['transfer_voucher'] = FileService::setFileUrl($params['transfer_voucher']);
        $withdrawApply = WithdrawApply::findOrEmpty($params['id']);
        $withdrawApply->status = WithdrawEnum::STATUS_SUCCESS;
        $withdrawApply->transfer_voucher = $params['transfer_voucher'];
        $withdrawApply->transfer_remark = $params['transfer_remark'];
        $withdrawApply->transfer_time = time();
        $withdrawApply->save();
        return true;
    }

    /**
     * @notes 转账失败
     * @param $params
     * @return bool
     * @author ljj
     * @date 2022/12/6 5:09 下午
     */
    public static function transferFail($params)
    {
        Db::startTrans();
        try {
            // 更新状态
            $withdrawApply = WithdrawApply::findOrEmpty($params['id']);
            $withdrawApply->status = WithdrawEnum::STATUS_FAIL;
            $withdrawApply->transfer_remark = $params['transfer_remark'] ?? '';
            $withdrawApply->transfer_voucher = $params['transfer_voucher'] ?? '';
            $withdrawApply->save();

            // 回退提现金额
            $user = User::findOrEmpty($withdrawApply->user_id);
            $user->user_earnings = $user->user_earnings + $withdrawApply->money;
            $user->save();

            // 记录账户流水
            AccountLogLogic::record($withdrawApply->user_id,AccountLogEnum::EAR,AccountLogEnum::EAR_INC_WITHDRAW_FAIL,AccountLogEnum::INC,$withdrawApply->money, $withdrawApply->sn);

            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}
