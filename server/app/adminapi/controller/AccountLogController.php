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

use app\adminapi\lists\AccountLogLists;
use app\common\enum\AccountLogEnum;

/***
 * 账户流水控制器
 * Class AccountLogController
 * @package app\adminapi\controller
 */
class AccountLogController extends BaseAdminController
{
    /**
     * @notes 查看账户流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function lists()
    {
        return $this->dataLists(new AccountLogLists());
    }

    /**
     * @notes 变动类型列表
     * @return array|\think\response\Json
     * @author ljj
     * @date 2022/5/31 5:06 下午
     */
    public function changeTypeLists()
    {
        $params = $this->request->get('change_object',1);
        if ($params == AccountLogEnum::BNW) {
            return $this->data(AccountLogEnum::getMoneyChangeTypeDesc());
        }
        if ($params == AccountLogEnum::EAR) {
            return $this->data(AccountLogEnum::getEarningsChangeTypeDesc());
        }

        return [];
    }



}