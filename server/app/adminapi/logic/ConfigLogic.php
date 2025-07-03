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

namespace app\adminapi\logic;

use app\adminapi\logic\dept\DeptLogic;
use app\common\enum\YesNoEnum;
use app\common\model\article\ArticleCate;
use app\common\model\auth\SystemMenu;
use app\common\model\auth\SystemRole;
use app\common\model\dept\Dept;
use app\common\model\dept\Jobs;
use app\common\model\dict\DictData;
use app\common\model\dict\DictType;
use app\common\service\{FileService, ConfigService};
use WpOrg\Requests\Requests;

/**
 * 配置类逻辑层
 * Class ConfigLogic
 * @package app\adminapi\logic
 */
class ConfigLogic
{
    /**
     * @notes 获取配置
     * @return array
     * @author 段誉
     * @date 2021/12/31 11:03
     */
    public static function getConfig(): array
    {
        $config = [
            // 文件域名
            'oss_domain' => FileService::getFileUrl('','domain'),

            // 网站名称
            'web_name' => ConfigService::get('website', 'name'),
            // 网站图标
            'web_favicon' => FileService::getFileUrl(ConfigService::get('website', 'web_favicon')),
            // 网站logo
            'web_logo' => FileService::getFileUrl(ConfigService::get('website', 'web_logo')),
            // 登录页
            'login_image' => FileService::getFileUrl(ConfigService::get('website', 'login_image')),

            // 版权信息
            'copyright_config' => ConfigService::get('copyright', 'config', []),

            //文档信息开关
            'document_status' => ConfigService::get('website','document_status',1),
            //版本号
            'version' => config('project.version'),
        ];
        return $config;
    }


    /**
     * @notes 根据类型获取字典类型
     * @param $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/27 19:09
     */
    public static function getDictByType($type)
    {
        if (!is_string($type)) {
            return [];
        }

        $type = explode(',', $type);
        $lists = DictData::whereIn('type_value', $type)->select()->toArray();

        if (empty($lists)) {
            return [];
        }

        $result = [];
        foreach ($type as $item) {
            foreach ($lists as $dict) {
                if ($dict['type_value'] == $item) {
                    $result[$item][] = $dict;
                }
            }
        }
        return $result;
    }


    /**
     * @notes 根据类型获取下拉框数据
     * @param $type
     * @return array
     * @author 段誉
     * @date 2022/9/27 19:32
     */
    public static function getSelectDataByType($type)
    {
        $allowData = [
            'dept' => Dept::class,
            'jobs' => Jobs::class,
            'role' => SystemRole::class,
            'dict_type' => DictType::class,
            'article_cate' => ArticleCate::class,
            'menu' => SystemMenu::class,
        ];
        if (!in_array($type, array_keys($allowData))) {
            return [];
        }

        $where = [];
        $order = [];
        switch ($type) {
            case 'dept':
            case 'jobs':
                $where[] = ['status', '=', YesNoEnum::YES];
                $order['sort'] = 'desc';
                break;
            case 'role':
                $order['sort'] = 'desc';
                break;
            case 'dict_type':
                $where[] = ['status', '=', YesNoEnum::YES];
                break;
            case 'article_cate':
                $where[] = ['is_show', '=', YesNoEnum::YES];
                $order['sort'] = 'desc';
                break;
            case 'menu':
                $order['sort'] = 'desc';
        }

        $order['id'] = 'desc';
        $model = $allowData[$type];
        $result = app($model)->where($where)->order($order)->select()->toArray();

        if ($type == 'dept' && !empty($result)) {
            $pid = min(array_column($result, 'pid'));
            $result = DeptLogic::getTree($result, $pid);
        }

        if ($type == 'menu' && !empty($result)) {
            $result = linear_to_tree($result, 'children');
        }

        return $result;
    }


    /**
     * @notes 正版检测
     * @return mixed
     * @author ljj
     * @date 2023/5/16 11:49 上午
     */
    public static function checkLegal()
    {
        $check_domain = config('project.check_domain');
        $product_code = config('project.product_code');
        $domain = $_SERVER['HTTP_HOST'];
        $result = Requests::get($check_domain.'/api/version/productAuth?code='.$product_code.'&domain='.$domain);
        $result = json_decode($result->body,true);
        return $result['data'];
    }

    /**
     * @notes 检测新版本
     * @return mixed
     * @author ljj
     * @date 2023/5/25 7:02 下午
     */
    public static function checkVersion()
    {
        $version = config('project.version');
        $product_code = config('project.product_code');
        $check_domain = config('project.check_domain');
        $result = Requests::get($check_domain.'/api/version/hasNew?code='.$product_code.'&version='.$version);
        $result = json_decode($result->body,true);
        return $result['data'];
    }

}