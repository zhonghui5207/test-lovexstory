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

namespace app\common\model\order;


use app\common\enum\OrderLogEnum;
use app\common\model\auth\Admin;
use app\common\model\BaseModel;
use app\common\model\user\User;

class OrderLog extends BaseModel
{
    /**
     * @notes 操作事件
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2022/2/11 2:21 下午
     */
    public function getChannelDescAttr($value,$data)
    {
        return OrderLogEnum::getRecordDesc($data['channel']);
    }


    public function getOperatorAttr($value,$data)
    {
        $operator = '未知';
        if ($data['type'] == OrderLogEnum::TYPE_SYSTEM) {
            $operator = '系统';
        }
        if ($data['type'] == OrderLogEnum::TYPE_ADMIN) {
            $operator = Admin::where('id',$data['operator_id'])->value('name');
        }
        if ($data['type'] == OrderLogEnum::TYPE_USER) {
            $operator = User::where('id',$data['operator_id'])->value('nickname');
        }
        return $operator;
    }
}