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

namespace app\adminapi\logic\setting\web;


use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 网站设置
 * Class WebSettingLogic
 * @package app\adminapi\logic\setting
 */
class WebSettingLogic extends BaseLogic
{

    /**
     * @notes 获取网站信息
     * @return array
     * @author 段誉
     * @date 2021/12/28 15:43
     */
    public static function getWebsiteInfo(): array
    {
        return [
            'name' => ConfigService::get('website', 'name'),
            'web_favicon' => FileService::getFileUrl(ConfigService::get('website', 'web_favicon')),
            'web_logo' => FileService::getFileUrl(ConfigService::get('website', 'web_logo')),
            'login_image' => FileService::getFileUrl(ConfigService::get('website', 'login_image')),
            'shop_name' => ConfigService::get('website', 'shop_name'),
            'shop_logo' => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),
            'contact_name' => ConfigService::get('website', 'contact_name'),
            'contact_phone' => ConfigService::get('website', 'contact_phone'),
            'document_status'       => ConfigService::get('website','document_status',1),
        ];
    }


    /**
     * @notes 设置网站信息
     * @param array $params
     * @author 段誉
     * @date 2021/12/28 15:43
     */
    public static function setWebsiteInfo(array $params)
    {
        $favicon = FileService::setFileUrl($params['web_favicon']);
        $logo = FileService::setFileUrl($params['web_logo']);
        $login = FileService::setFileUrl($params['login_image']);
        $shopLogo = FileService::setFileUrl($params['shop_logo']);

        ConfigService::set('website', 'name', $params['name']);
        ConfigService::set('website', 'web_favicon', $favicon);
        ConfigService::set('website', 'web_logo', $logo);
        ConfigService::set('website', 'login_image', $login);
        ConfigService::set('website', 'shop_name', $params['shop_name']);
        ConfigService::set('website', 'shop_logo', $shopLogo);
        ConfigService::set('website', 'contact_name', $params['contact_name']);
        ConfigService::set('website', 'contact_phone', $params['contact_phone']);

        //文档信息开关
        ConfigService::set('website','document_status', $params['document_status']);
    }


    /**
     * @notes 获取版权备案
     * @return array
     * @author 段誉
     * @date 2021/12/28 16:09
     */
    public static function getCopyright() : array
    {
        return ConfigService::get('copyright', 'config', []);
    }


    /**
     * @notes 设置版权备案
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/8/8 16:33
     */
    public static function setCopyright(array $params)
    {
        try {
            if (!is_array($params['config'])) {
                throw new \Exception('参数异常');
            }
            ConfigService::set('copyright', 'config', $params['config'] ?? []);
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 设置政策协议
     * @param array $params
     * @author ljj
     * @date 2022/2/15 10:59 上午
     */
    public static function setAgreement(array $params)
    {
        $serviceContent = clear_file_domain($params['service_content'] ?? '');
        $privacyContent = clear_file_domain($params['privacy_content'] ?? '');
        ConfigService::set('agreement', 'service_title', $params['service_title'] ?? '');
        ConfigService::set('agreement', 'service_content', $serviceContent);
        ConfigService::set('agreement', 'privacy_title', $params['privacy_title'] ?? '');
        ConfigService::set('agreement', 'privacy_content', $privacyContent);
    }


    /**
     * @notes 获取政策协议
     * @return array
     * @author ljj
     * @date 2022/2/15 11:15 上午
     */
    public static function getAgreement() : array
    {
        $config = [
            'service_title' => ConfigService::get('agreement', 'service_title'),
            'service_content' => ConfigService::get('agreement', 'service_content'),
            'privacy_title' => ConfigService::get('agreement', 'privacy_title'),
            'privacy_content' => ConfigService::get('agreement', 'privacy_content'),
        ];

        $config['service_content'] = get_file_domain($config['service_content']);
        $config['privacy_content'] = get_file_domain($config['privacy_content']);

        return $config;
    }
}