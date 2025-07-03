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
use app\common\service\{
    FileService,
    ConfigService
};

/**
 * 配置逻辑层
 * Class CollectLogic
 * @package app\common\logic
 */
class ConfigLogic
{
    /**
     * @notes 获取商城配置
     * @return array
     * @author cjhao
     * @date 2021/8/28 17:23
     */
    public function getConfig():array
    {
        $config = [
            //注册方式
            'register_way'              => ConfigService::get('config', 'register_way',  config('project.login.register_way')),
            //登录方式
            'login_way'                 => ConfigService::get('config', 'login_way',config('project.login.login_way')),
            //手机号码注册需验证码
            'is_mobile_register_code'   => ConfigService::get('config', 'is_mobile_register_code',  config('project.login.is_mobile_register_code')),
            //注册强制绑定手机
            'coerce_mobile'             => ConfigService::get('config', 'coerce_mobile', config('project.login.coerce_mobile')),
            //公众号微信授权登录
            'h5_wechat_auth'            => ConfigService::get('config', 'h5_wechat_auth', config('project.login.h5_wechat_auth')),
            //公众号自动微信授权登录
            'h5_auto_wechat_auth'       => ConfigService::get('config', 'h5_auto_wechat_auth', config('project.login.h5_auto_wechat_auth')),
            //小程序微信授权登录
            'mnp_wechat_auth'           => ConfigService::get('config', 'mnp_wechat_auth', config('project.login.mnp_wechat_auth')),
            //小程序自动微信授权登录
            'mnp_auto_wechat_auth'      => ConfigService::get('config', 'mnp_auto_wechat_auth',  config('project.login.mnp_auto_wechat_auth')),
            //oss域名
            'oss_domain'                => FileService::getFileUrl('','domain'),
            //logo
            'logo'                      => FileService::getFileUrl(ConfigService::get('website', 'web_logo')),
            //网站昵称
            'name'                      => ConfigService::get('website', 'name'),
            // 版权信息
            'copyright'                 => ConfigService::get('shop', 'copyright', ''),
            // 备案号
            'record_number'             => ConfigService::get('shop', 'record_number', ''),
            //默认头像
            'default_avatar'            => ConfigService::get('config', 'default_avatar',  FileService::getFileUrl(config('project.default_image.user_avatar'))),
        ];
        return $config;
    }

    /**
     * @notes 政策协议
     * @return array
     * @author ljj
     * @date 2022/2/23 11:42 上午
     */
    public function agreement()
    {
        $config = [
            'service_title' => ConfigService::get('agreement', 'service_title'),
            'service_content' => ConfigService::get('agreement', 'service_content'),
            'privacy_title' => ConfigService::get('agreement', 'privacy_title'),
            'privacy_content' => ConfigService::get('agreement', 'privacy_content'),
        ];
        return $config;
    }
}