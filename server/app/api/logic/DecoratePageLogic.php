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
use app\common\model\decorate\DecoratePage;

class DecoratePageLogic
{
    /**
     * @notes 获取装修页面
     * @param int $type
     * @return array
     * @author cjhao
     * @date 2022/6/29 17:57
     */
    public function getDecorate(int $type):array
    {
        $decoratePage = DecoratePage::where(['type' => $type])
            ->field('content,common')
            ->findOrEmpty()->toArray();
        $decoratePage = \app\common\logic\DecoratePageLogic::getModuleData($decoratePage);
        return $decoratePage;

    }

}