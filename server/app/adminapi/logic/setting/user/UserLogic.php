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
namespace app\adminapi\logic\setting\user;

use app\common\enum\WithdrawEnum;
use app\common\service\{ConfigService, FileService};

/**
 * 设置-用户设置逻辑层
 * Class UserLogic
 * @package app\adminapi\logic\config
 */
class UserLogic
{

    /**
     * @notes 获取用户设置
     * @return array
     * @author 段誉
     * @date 2022/3/29 10:09
     */
    public static function getConfig(): array
    {
        $defaultAvatar = config('project.default_image.user_avatar');
        $config = [
            //默认头像
            'default_avatar' => FileService::getFileUrl(ConfigService::get('default_image', 'user_avatar', $defaultAvatar)),
        ];
        return $config;
    }


    /**
     * @notes 设置用户设置
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/3/29 10:09
     */
    public function setConfig(array $params): bool
    {
        $avatar = FileService::setFileUrl($params['default_avatar']);
        ConfigService::set('default_image', 'user_avatar', $avatar);
        return true;
    }


    /**
     * @notes 获取注册配置
     * @return array
     * @author 段誉
     * @date 2022/3/29 10:10
     */
    public function getRegisterConfig(): array
    {
        $config = [
            // 登录方式
            'login_way' => ConfigService::get('login', 'login_way', config('project.login.login_way')),
            // 注册强制绑定手机
            'coerce_mobile' => ConfigService::get('login', 'coerce_mobile', config('project.login.coerce_mobile')),
            // 政策协议
            'login_agreement' => ConfigService::get('login', 'login_agreement', config('project.login.login_agreement')),
            // 第三方登录 开关
            'third_auth' => ConfigService::get('login', 'third_auth', config('project.login.third_auth')),
            // 微信授权登录
            'wechat_auth' => ConfigService::get('login', 'wechat_auth', config('project.login.wechat_auth')),
            // qq授权登录
            'qq_auth' => ConfigService::get('login', 'qq_auth', config('project.login.qq_auth')),
        ];
        return $config;
    }


    /**
     * @notes 设置登录注册
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/3/29 10:10
     */
    public static function setRegisterConfig(array $params): bool
    {
        // 登录方式：1-账号密码登录；2-手机短信验证码登录
        ConfigService::set('login', 'login_way', $params['login_way']);
        // 注册强制绑定手机
        ConfigService::set('login', 'coerce_mobile', $params['coerce_mobile']);
        // 政策协议
        ConfigService::set('login', 'login_agreement', $params['login_agreement']);
        // 第三方授权登录
        ConfigService::set('login', 'third_auth', $params['third_auth']);
        // 微信授权登录
        ConfigService::set('login', 'wechat_auth', $params['wechat_auth']);
        // qq登录
        ConfigService::set('login', 'qq_auth', $params['qq_auth']);
        return true;
    }

    /**
     * @notes 获取提现配置
     * @return array
     * @author ljj
     * @date 2022/11/12 19:26
     */
    public function getWithdrawConfig():array
    {
        $config = [
            //提现方式：1-钱包余额；2-微信零钱；3-银行卡；4-微信收款码；5-支付宝收款码
            'withdraw_way' => ConfigService::get('config', 'withdraw_way',[1]),
            //微信零钱接口：1-商家转账到零钱
            'transfer_way' => ConfigService::get('config', 'transfer_way',1),
            //最低提现金额
            'withdraw_min_money' => ConfigService::get('config', 'withdraw_min_money', WithdrawEnum::DEFAULT_MIN_MONEY),
            //最高提现金额
            'withdraw_max_money' => ConfigService::get('config', 'withdraw_max_money', WithdrawEnum::DEFAULT_MAX_MONEY),
            //提现手续费
            'withdraw_service_charge' => ConfigService::get('config', 'withdraw_service_charge', WithdrawEnum::DEFAULT_PERCENTAGE),
        ];
        return $config;

    }

    /**
     * @notes 设置提现配置
     * @param array $params
     * @return bool
     * @author ljj
     * @date 2022/11/12 19:25
     */
    public function setWithdrawConfig(array $params):bool
    {
        //提现方式：1-钱包余额；2-微信零钱；3-银行卡；4-微信收款码；5-支付宝收款码
        ConfigService::set('config', 'withdraw_way',$params['withdraw_way']);
        //微信零钱接口：1-商家转账到零钱
        ConfigService::set('config', 'transfer_way',$params['transfer_way']);
        //最低提现金额
        ConfigService::set('config', 'withdraw_min_money', $params['withdraw_min_money']);
        //最高提现金额
        ConfigService::set('config', 'withdraw_max_money', $params['withdraw_max_money']);
        //提现手续费
        ConfigService::set('config', 'withdraw_service_charge', $params['withdraw_service_charge']);
        return true;

    }

}