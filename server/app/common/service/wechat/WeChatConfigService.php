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
namespace app\common\service\wechat;

use app\common\enum\PayEnum;
use app\common\enum\UserTerminalEnum;
use app\common\model\pay\PayConfig;
use app\common\service\ConfigService;

/**
 * 微信配置类
 * Class WeChatConfigService
 * @package app\common\service
 */
class WeChatConfigService
{
    //根据用户客户端不同获取不同的微信配置
    public static function getWechatConfigByTerminal($terminal)
    {
        switch ($terminal) {
            case UserTerminalEnum::WECHAT_MMP:
                $appid = ConfigService::get('mnp_setting', 'app_id');
                $secret = ConfigService::get('mnp_setting', 'app_secret');
                $notify_url = (string)url('pay/notifyMnp', [], false, true);
                break;
            case UserTerminalEnum::WECHAT_OA:
            case UserTerminalEnum::PC:
            case UserTerminalEnum::H5:
                $appid = ConfigService::get('oa_setting', 'app_id');
                $secret = ConfigService::get('oa_setting', 'app_secret');
                $notify_url = (string)url('pay/notifyOa', [], false, true);
                break;
            case UserTerminalEnum::ANDROID:
            case UserTerminalEnum::IOS:
                $appid = ConfigService::get('open_platform', 'app_id');
                $secret = ConfigService::get('open_platform', 'app_secret');
                $notify_url = (string)url('pay/notifyApp', [], false, true);
                break;
            default:
                $appid = '';
                $secret = '';
        }
        $pay = PayConfig::where(['pay_way' => PayEnum::WECHAT_PAY])->findOrEmpty()->toArray();
        //判断是否已经存在证书文件夹，不存在则新建
        if (!file_exists(app()->getRootPath().'runtime/certificate')) {
            mkdir(app()->getRootPath().'runtime/certificate', 0775, true);
        }
        //写入文件
        $apiclient_cert = $pay['config']['apiclient_cert'] ?? '';
        $apiclient_key = $pay['config']['apiclient_key'] ?? '';
        $cert_path = app()->getRootPath().'runtime/certificate/'.md5($apiclient_cert).'.pem';
        $key_path = app()->getRootPath().'runtime/certificate/'.md5($apiclient_key).'.pem';
        if (!file_exists($cert_path)) {
            $fopen_cert_path = fopen($cert_path, 'w');
            fwrite($fopen_cert_path, $apiclient_cert);
            fclose($fopen_cert_path);
        }
        if (!file_exists($key_path)) {
            $fopen_key_path = fopen($key_path, 'w');
            fwrite($fopen_key_path, $apiclient_key);
            fclose($fopen_key_path);
        }
        $config = [
            'app_id' => $appid,
            'secret' => $secret,
            'mch_id' => $pay['config']['mch_id'] ?? '',
            'key' => $pay['config']['pay_sign_key'] ?? '',
            'cert_path' => $cert_path,
            'key_path' => $key_path,
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => '../runtime/log/wechat.log'
            ],
            'notify_url' => $notify_url
        ];

        return $config;
    }

    /**
     * @notes 获取小程序配置
     * @return array
     * @author 段誉
     * @date 2022/9/6 19:49
     */
    public static function getMnpConfig()
    {
        $config = [
            'app_id' => ConfigService::get('mnp_setting', 'app_id'),
            'secret' => ConfigService::get('mnp_setting', 'app_secret'),
            'mch_id' => ConfigService::get('mnp_setting', 'mch_id'),
            'key' => ConfigService::get('mnp_setting', 'key'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => '../runtime/log/wechat.log'
            ],
        ];
        return $config;
    }


    /**
     * @notes 获取微信公众号配置
     * @return array
     * @author 段誉
     * @date 2022/9/6 19:49
     */
    public static function getOaConfig()
    {
        $config = [
            'app_id' => ConfigService::get('oa_setting', 'app_id'),
            'secret' => ConfigService::get('oa_setting', 'app_secret'),
            'mch_id' => ConfigService::get('oa_setting', 'mch_id'),
            'key' => ConfigService::get('oa_setting', 'key'),
            'token' => ConfigService::get('oa_setting', 'token'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => '../runtime/log/wechat.log'
            ],
        ];
        return $config;
    }


}