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

use app\common\logic\BaseLogic;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;
use think\facade\Config;

/**
 * 注册逻辑层
 * Class RegisterLogic
 * @package app\api\logic
 */
class RegisterLogic extends BaseLogic
{
    public static function register($params)
    {
        try {
            $defaultAvatar = ConfigService::get('default_image', 'user_avatar');
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            // 创建用户
            $data = [
                'register_source' => $params['register_source'],
                'sn' => create_user_sn(),
                'nickname' => $params['mobile'],
                'avatar' => $defaultAvatar,
                'mobile' => $params['mobile'],
                'user_money' => 0,
                'user_integral' => 0,
                'total_order_amount' => 0,
                'total_order_num' => 0,
                'account' => $params['mobile'],
                'password' => $password,
                'user_growth' => 0,
            ];
            User::create($data);
            // 注册奖励
//            \app\common\logic\UserLogic::registerAward($user->id);

            return true;
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }




}