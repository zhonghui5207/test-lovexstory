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
namespace app\api\logic;

use app\common\enum\UserTerminalEnum;
use app\common\model\distribution\Distribution;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\sms\SmsDriver;
use app\common\service\wechat\WeChatConfigService;
use EasyWeChat\Factory;
use think\Exception;
use think\facade\Config;
use app\common\logic\BaseLogic;
/**
 * 会员逻辑层
 * Class UserLogic
 * @package app\api\logic
 */
class UserLogic extends BaseLogic
{

    /**
     * @notes 个人中心
     * @param array $userInfo
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 18:04
     */
    public static function center(array $userInfo): array
    {
        $user = User::where(['id' => $userInfo['user_id']])
            ->field('id,sn,sex,account,is_new_user,nickname,real_name,avatar,mobile,create_time')
            ->findOrEmpty();
        //是否有微信授权登录
        if (in_array($userInfo['terminal'], [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA])) {
            $auth = UserAuth::where(['user_id' => $userInfo['user_id'], 'terminal' => $userInfo['terminal']])->find();
            $user->is_auth = $auth ? 1 : 0;
        }
        //获取分销商信息
        $distribution = Distribution::where(['user_id'=>$userInfo['user_id']])->findOrEmpty()->toArray();
        $user->code = $distribution['code'] ?? '';

        return $user->toArray();
    }


    /**
     * @notes 设置用户信息
     * @param int $userId
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/6 19:44
     */
    public static function setInfo(int $userId, array $params): bool
    {
        User::update(['id' => $userId, $params['field'] => $params['value']]);
        return true;
    }

    /**
     * @notes 绑定手机号
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/25 17:55
     */
    public static function bindMobile($params)
    {
        try {
            $smsDriver = new SmsDriver();
            $result = $smsDriver->verify($params['mobile'], $params['code']);
            if (!$result) {
                throw new \Exception('验证码错误');
            }
            $user = User::where('mobile', $params['mobile'])->findOrEmpty();
            if (!$user->isEmpty()) {
                throw new \Exception('该手机号已被其他账号绑定');
            }
            unset($params['code']);
            User::update($params);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 判断用户是否已设置支付密码
     * @param $userId
     * @return bool
     * @author Tab
     * @date 2021/8/17 10:31
     */
    public static function hasPayPassword($userId)
    {
        $user = User::findOrEmpty($userId);
        if (empty($user->pay_password)) {
            return ['has_pay_password' => false];
        }
        return ['has_pay_password' => true];
    }

    /**
     * @notes 设置/修改交易密码
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/12 11:32
     */
    public static function setPayPassword($params)
    {
        try {
            $user = User::findOrEmpty($params['user_id']);
            if (empty($user->pay_password)) {
                // 首次设置密码
                $user->pay_password = md5($params['pay_password']);
                $user->save();
                return true;
            }
            // 修改密码
            if (!isset($params['origin_pay_password']) || empty($params['origin_pay_password'])) {
                throw new \think\Exception('请输入原支付密码');
            }
            if ($user->pay_password != md5($params['origin_pay_password'])) {
                throw new \think\Exception('原支付密码错误');
            }
            // 设置新支付密码
            $user->pay_password = md5($params['pay_password']);
            $user->save();

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 重置支付密码
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/24 16:30
     */
    public static function resetPayPassword($params)
    {
        try {
            // 校验验证码
            $smsDriver = new SmsDriver();
            if (!$smsDriver->verify($params['mobile'], $params['code'])) {
                throw new \Exception('验证码错误');
            }
            $params['pay_password'] = md5($params['pay_password']);
            unset($params['code']);
            unset($params['mobile']);
            User::update($params);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 重置登录密码
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/25 17:03
     */
    public static function resetPassword($params)
    {
        try {
            // 校验验证码
            $smsDriver = new SmsDriver();
            if (!$smsDriver->verify($params['mobile'], $params['code'])) {
                throw new \Exception('验证码错误');
            }
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);

            User::where('mobile', $params['mobile'])->update([
                'password' => $password
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 钱包
     * @param $userId
     * @return array
     * @author Tab
     * @date 2021/8/12 14:58
     */
    public function wallet($userId)
    {
        $result = User::where(['id' => $userId])
            ->field('id,user_money,user_earnings')
            ->findOrEmpty()
            ->toArray();
        $result['total_money'] = round($result['user_money'] + $result['user_earnings'],2);
        $result['recharge_open'] = ConfigService::get('recharge', 'open',0);

        return $result;
    }

    /**
     * @notes 用户信息
     * @param $userId
     * @return array
     * @author Tab
     * @date 2021/8/25 17:22
     */
    public function info(array $userInfo)
    {
        $user = User::where(['id' => $userInfo['user_id']])
            ->field('id,sn,sex,account,password,nickname,real_name,avatar,mobile,create_time')
            ->findOrEmpty();
        $user['has_password'] = !empty($user['password']);
        $user['has_auth'] = self::hasWechatAuth($userInfo['user_id']);
        $user['version'] = config('project.version');
        $user->hidden(['password']);
        return $user->toArray();
    }
    
    /**
     * @notes 是否有微信授权信息
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:36
     */
    public static function hasWechatAuth(int $userId)
    {
        //是否有微信授权登录
        $terminal = [\app\common\enum\user\UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA];
        $auth = UserAuth::where(['user_id' => $userId])
            ->whereIn('terminal', $terminal)
            ->findOrEmpty();
        return !$auth->isEmpty();
    }

    /**
     * @notes 微信小程序获取手机号码并绑定
     * @param $params
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author cjhao
     * @date 2022/5/17 9:34
     */
    public static function getMobileByMnp($params)
    {
        try {
            $getMnpConfig = WeChatConfigService::getMnpConfig();
            $app = Factory::miniProgram($getMnpConfig);
            $response = $app->phone_number->getUserPhoneNumber($params['code']);

            $phoneNumber = $response['phone_info']['purePhoneNumber'] ?? '';
            if(empty($phoneNumber)){
                throw new Exception('获取手机号码失败');
            }
            $user = User::where([
                ['mobile', '=', $phoneNumber],
                ['id', '<>', $params['user_id']]
            ])->findOrEmpty();

            if (!$user->isEmpty()) {
                throw new \Exception('手机号已被其他账号绑定');
            }

            // 绑定手机号
            self::setInfo($params['user_id'], [
                'field' => 'mobile',
                'value' => $phoneNumber
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取服务协议
     * @return array
     * @author Tab
     * @date 2021/8/24 16:48
     */
    public static function getServiceAgreement()
    {
        $data = [
            'service_agreement_name' => ConfigService::get('shop', 'service_agreement_name', ''),
            'service_agreement_content' => ConfigService::get('shop', 'service_agreement_content', ''),
        ];
        return $data;
    }

    /**
     * @notes 获取隐私政策
     * @return array
     * @author Tab
     * @date 2021/8/24 16:50
     */
    public static function getPrivacyPolicy()
    {
        $data = [
            'privacy_policy_name' => ConfigService::get('shop', 'privacy_policy_name', ''),
            'privacy_policy_content' => ConfigService::get('shop', 'privacy_policy_content', '')
        ];
        return $data;
    }

    /**
     * @notes 设置登录密码
     * @author Tab
     * @date 2021/10/22 18:10
     */
    public static function setPassword($params)
    {
        try {
            $user = User::findOrEmpty($params['user_id']);
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }
            if (!empty($user->password)) {
                throw new \Exception('用户已设置登录密码');
            }
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            $user->password = $password;
            $user->save();

            return true;;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 修改登录密码
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/10/22 18:17
     */
    public static function changePassword($params)
    {
        try {
            $user = User::findOrEmpty($params['user_id']);
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }
            $passwordSalt = Config::get('project.unique_identification');
            if($user->password && $params['old_password']){
                $oldPassword = create_password($params['old_password'], $passwordSalt);
                if ($user->password != $oldPassword) {
                    throw new \Exception('原密码错误');
                }
            }
            $newPassword = create_password($params['password'], $passwordSalt);
            $user->password = $newPassword;
            $user->save();

            return true;;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 判断用户是否有设置登录密码
     * @param $userId
     * @author Tab
     * @date 2021/10/22 18:25
     */
    public static function hasPassword($userId)
    {
        $user = User::findOrEmpty($userId);
        return empty($user->password) ? false : true;
    }

    /**
     * @notes 客服配置
     * @return array
     * @author ljj
     * @date 2022/2/24 2:53 下午
     */
    public function customerService()
    {
        $qrCode = ConfigService::get('customer_service', 'qr_code');
        $qrCode = empty($qrCode) ? '' : FileService::getFileUrl($qrCode);
        $config = [
            'qr_code' => $qrCode,
            'wechat' => ConfigService::get('customer_service', 'wechat', ''),
            'phone' => ConfigService::get('customer_service', 'phone', ''),
            'service_time' => ConfigService::get('customer_service', 'service_time', ''),
        ];
        return $config;
    }


}