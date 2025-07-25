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

namespace app\adminapi\controller;

use app\adminapi\logic\auth\AuthLogic;
use app\adminapi\logic\ConfigLogic;

/**
 * 配置控制器123
 * Class ConfigController
 * @package app\adminapi\controller
 */
class ConfigController extends BaseAdminController
{
    public array $notNeedLogin = ['getConfig', 'dict', 'selectData'];


    /**
     * @notes 基础配置2
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/31 11:01
     */
    public function getConfig()
    {
        $data = ConfigLogic::getConfig();
        return $this->data($data);
    }


    /**
     * @notes 根据类型获取字典数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/27 19:10
     */
    public function dict()
    {
        $type = $this->request->get('type', '');
        $data = ConfigLogic::getDictByType($type);
        return $this->data($data);
    }


    /**
     * @notes 根据类型获取下拉框数据
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/27 19:33
     */
    public function selectData()
    {
        $type = $this->request->get('type', '');
        $data = ConfigLogic::getSelectDataByType($type);
        return $this->data($data);
    }


    /**
     * @notes 正版检测
     * @return \think\response\Json
     * @author ljj
     * @date 2023/5/16 11:49 上午
     */
    public function checkLegal()
    {
        $data = ConfigLogic::checkLegal();
        return $this->data($data);
    }

    /**
     * @notes 检测新版本
     * @return \think\response\Json
     * @author ljj
     * @date 2023/5/25 7:02 下午
     */
    public function checkVersion()
    {
        $data = ConfigLogic::checkVersion();
        return $this->data($data);
    }


}