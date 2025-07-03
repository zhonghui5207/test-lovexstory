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
use app\api\logic\ConfigLogic;

/**
 * 配置控制器
 * Class ConfigController
 * @package app\api\controller
 */
class ConfigController extends BaseApiController
{
    public array $notNeedLogin = ['config', 'getPolicyAgreement'];

    /**
     * @notes 获取商城配置
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/28 17:23
     */
    public function config()
    {
        $data = (new ConfigLogic())->getConfig();
        return $this->success('',$data);
    }

    /**
     * @notes 政策协议
     * @return \think\response\Json
     * @author ljj
     * @date 2022/2/23 11:42 上午
     */
    public function agreement()
    {
        $result = (new ConfigLogic())->agreement();
        return $this->success('获取成功',$result);
    }
}