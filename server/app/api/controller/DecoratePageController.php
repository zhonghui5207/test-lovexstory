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
use app\common\enum\DecorateEnum;
use app\api\logic\DecoratePageLogic;

/**
 * 装修控制器类
 * Class DecoratePageController
 * @package app\api\controller
 */
class DecoratePageController extends BaseApiController
{

    public array $notNeedLogin = ['getDecorate'];
    /**
     * @notes 获取装修数据
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/29 17:58
     */
    public function getDecorate()
    {
        $type = $this->request->get('type',DecorateEnum::TYPE_INDEX);
        $data = (new DecoratePageLogic())->getDecorate($type);
        return $this->success('',$data);
    }

}