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

namespace app\api\controller;

use app\common\enum\NoticeEnum;
use app\api\logic\RegisterLogic;
use app\api\validate\RegisterValidate;

/**
 * 注册控制器
 * Class RegisterController
 * @package app\api\controller
 */
class RegisterController extends BaseApiController
{
    public array $notNeedLogin = ['captcha', 'register'];

    /**
     * @notes 发送验证码 - 注册
     * @author Tab
     * @date 2021/8/25 11:20
     */
    public function captcha()
    {
        $params = (new RegisterValidate())->post()->goCheck('captcha');
        $code = mt_rand(1000, 9999);
        $result = event('Notice', [
            'scene_id' => NoticeEnum::REGISTER_CAPTCHA,
            'params' => [
                'mobile' => $params['mobile'],
                'code' => $code,
            ]
        ]);

        if ($result[0] === true) {
            return $this->success('发送成功');
        }

        return $this->fail($result[0], [], 0, 1);
    }

    /**
     * @notes 手机号注册
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/25 11:47
     */
    public function register()
    {
        $params = (new RegisterValidate())->post()->goCheck('register');
        $result = RegisterLogic::register($params);
        if($result) {
            return $this->success('注册成功', [], 1, 1);
        }
        return $this->fail(RegisterLogic::getError());
    }
}