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

namespace app\api\lists;

use app\common\enum\AccountLogEnum;
use app\common\model\AccountLog;

/**
 * 账户流水列表
 * Class AccountLogLists
 * @package app\api\lists
 */
class AccountLogLists extends BaseApiDataLists
{
    /**
     * @notes 设置搜索条件
     * @author Tab
     * @date 2021/8/9 17:31
     */
    public function setWhere()
    {
        // 指定用户
        $this->searchWhere[] = ['user_id', '=', $this->userId];

        if(isset($this->params['type']) && '' !=$this->params['type']) {
            $this->searchWhere[] = ['action', '=', $this->params['type']];
        }

        if (isset($this->params['change_object']) && $this->params['change_object'] != '') {
            $this->searchWhere[] = ['change_object','=',$this->params['change_object']];
        }else {
            $this->searchWhere[] = ['change_object','=',AccountLogEnum::BNW];
        }

    }

    /**
     * @notes 账户流水列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Tab
     * @date 2021/8/9 17:37
     */
    public function lists(): array
    {
        // 设置搜索条件
        $this->setWhere();

        $field = 'change_type,change_amount,action,create_time';
        $lists = AccountLog::field($field)
            ->where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)
            ->order('id', 'desc')
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            $item['type_desc'] = AccountLogEnum::getChangeTypeDesc($item['change_type']);
        }

        return $lists;
    }

    /**
     * @notes 账户流水记录数
     * @return int
     * @author Tab
     * @date 2021/8/9 17:36
     */
    public function count(): int
    {
        // 设置搜索条件
        $this->setWhere();

        $count = AccountLog::where($this->searchWhere)->count();
        return $count;
    }
}