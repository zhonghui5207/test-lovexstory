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

namespace app\adminapi\validate\finance;

use app\common\enum\WithdrawEnum;
use app\common\model\WithdrawApply;
use app\common\model\user\UserAuth;
use app\common\validate\BaseValidate;


class WithdrawValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'verify_remark' => 'require',
        'transfer_voucher' => 'require',
        'transfer_remark' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'verify_remark.require' => '请输入审核说明',
        'transfer_voucher.require' => '请上传转账凭证',
        'transfer_remark.require' => '请填写转账说明',
    ];


    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneRefuse()
    {
        return $this->only(['id','verify_remark'])
            ->append('id','checkRefuse');
    }

    public function scenePass()
    {
        return $this->only(['id'])
            ->append('id','checkPass');
    }

    public function sceneSearch()
    {
        return $this->only(['id'])
            ->append('id','checkSearch');
    }

    public function sceneTransferSuccess()
    {
        return $this->only(['id','transfer_voucher','transfer_remark'])
            ->append('id','checkTransferSuccess');
    }

    public function sceneTransferFail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 校验审核拒绝
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/12/6 3:40 下午
     */
    public function checkRefuse($value,$rule,$data)
    {
        $result = WithdrawApply::findOrEmpty($value);
        if ($result->isEmpty()) {
            return '提现申请不存在';
        }
        if($result->status != WithdrawEnum::STATUS_WAIT) {
            return '不是待提现状态,不允许审核';
        }
        return true;
    }

    /**
     * @notes 校验审核通过
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/12/6 3:55 下午
     */
    public function checkPass($value,$rule,$data)
    {
        $result = WithdrawApply::findOrEmpty($value);
        if ($result->isEmpty()) {
            return '提现申请不存在';
        }
        if($result->status != WithdrawEnum::STATUS_WAIT) {
            return '不是待提现状态,不允许审核';
        }
        if ($result['type'] == WithdrawEnum::TYPE_WECHAT_CHANGE) {
            // 校验条件
            if($result['left_money'] < 1) {
                return '扣除手续费后提现金额不能小于1元';
            }
            $count = WithdrawApply::whereDay('update_time')->where([
                ['user_id', '=', $result['user_id']],
                ['type', '=', WithdrawEnum::TYPE_WECHAT_CHANGE],
                ['status', 'in', [WithdrawEnum::STATUS_ING,WithdrawEnum::STATUS_SUCCESS,WithdrawEnum::STATUS_FAIL]],
            ])->count();
            if($count >= 10) {
                return '同一天向同一个用户最多付款10次';
            }
            // 用户授权信息
            $userAuth = UserAuth::where('user_id', $result['user_id'])->order('terminal', 'asc')->findOrEmpty();
            if($userAuth->isEmpty()) {
                return '获取不到用户的openid';
            }
        }
        return true;
    }

    /**
     * @notes 校验查询
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/12/6 4:40 下午
     */
    public function checkSearch($value,$rule,$data)
    {
        $result = WithdrawApply::findOrEmpty($value);
        if ($result->isEmpty()) {
            return '提现申请不存在';
        }
        if($result['status'] != WithdrawEnum::STATUS_ING) {
            return '非提现中状态无法查询结果';
        }
        if($result['type'] != WithdrawEnum::TYPE_WECHAT_CHANGE) {
            return '非微信零钱提现方式无法查询结果';
        }
        return true;
    }

    /**
     * @notes 校验转账成功
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/12/6 4:54 下午
     */
    public function checkTransferSuccess($value,$rule,$data)
    {
        $result = WithdrawApply::findOrEmpty($value);
        if ($result->isEmpty()) {
            return '提现申请不存在';
        }
        if($result['status'] != WithdrawEnum::STATUS_ING) {
            return '非提现中状态无法转账';
        }
        if($result['type'] != WithdrawEnum::TYPE_BANK && $result['type'] != WithdrawEnum::TYPE_WECHAT_CODE && $result['type'] != WithdrawEnum::TYPE_ALI_CODE) {
            return '该提现无需转账';
        }
        return true;
    }
}