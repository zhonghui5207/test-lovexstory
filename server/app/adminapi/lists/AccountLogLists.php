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

namespace app\adminapi\lists;

use app\common\enum\AccountLogEnum;
use app\common\lists\ListsExcelInterface;
use app\common\lists\ListsSearchInterface;
use app\common\model\AccountLog;
use app\common\service\FileService;

/**
 * 账记流水列表
 * Class AccountLogLists
 * @package app\adminapi\lists
 */
class AccountLogLists extends BaseAdminDataLists implements ListsExcelInterface
{
    /**
     * @notes 导出表名
     * @return string
     * @author Tab
     * @date 2021/9/22 18:40
     */
    public function setFileName(): string
    {
        return '余额明细';
    }

    /**
     * @notes 导出字段
     * @return array
     * @author Tab
     * @date 2021/9/22 18:40
     */
    public function setExcelFields(): array
    {
        return [
            'nickname' => '用户昵称',
            'sn' => '用户编号',
            'mobile' => '手机号码',
            'change_amount' => '变动金额',
            'left_amount' => '剩余金额',
            'change_type_desc' => '变动类型',
            'association_sn' => '来源单号',
            'create_time' => '记录时间',
        ];
    }

    /**
     * @notes 设置搜索
     * @return array
     * @author Tab
     * @date 2021/8/12 15:32
     */
    public function where(): array
    {
        $where = [];
        if(isset($this->params['user_info']) && $this->params['user_info']){
            $where[] = ['u.sn|u.nickname|u.mobile','like','%'.$this->params['user_info'].'%'];
        }
        if(isset($this->params['change_type']) && $this->params['change_type'] != ''){
            $where[] = ['al.change_type','=',$this->params['change_type']];
        }
        if(isset($this->params['start_time']) && $this->params['start_time']){
            $where[] = ['al.create_time','>=',strtotime($this->params['start_time'])];
        }
        if(isset($this->params['end_time']) && $this->params['end_time']){
            $where[] = ['al.create_time','<=',strtotime($this->params['end_time'])];
        }
        return $where;
    }

    /**
     * @notes 账户流水列表
     * @return array
     * @author Tab
     * @date 2021/8/12 15:32
     */
    public function lists(): array
    {
        $where = [];
        if (isset($this->params['change_object']) && $this->params['change_object'] != '') {
            $where[] = ['al.change_object','=',$this->params['change_object']];
        }else {
            $where[] = ['al.change_object','=',AccountLogEnum::BNW];
        }
        $field = 'u.nickname,u.avatar,u.sn,u.mobile,al.action,al.change_amount,al.left_amount,al.change_type,al.association_sn,al.create_time';
        $lists = AccountLog::alias('al')
            ->leftJoin('user u', 'u.id = al.user_id')
            ->field($field)
            ->where($this->where())
            ->where($where)
            ->order('al.id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            $item['change_type_desc'] = AccountLogEnum::getChangeTypeDesc( $item['change_type']);
            $item['avatar'] = FileService::getFileUrl( $item['avatar']);
        }

        return $lists;
    }

    /**
     * @notes 账户流水数量
     * @return int
     * @author Tab
     * @date 2021/8/12 15:33
     */
    public function count(): int
    {
        $where = [];
        if (isset($this->params['change_object']) && $this->params['change_object'] != '') {
            $where[] = ['al.change_object','=',$this->params['change_object']];
        }else {
            $where[] = ['al.change_object','=',AccountLogEnum::BNW];
        }
        $count = AccountLog::alias('al')
            ->leftJoin('user u', 'u.id = al.user_id')
            ->where($this->where())
            ->where($where)
            ->count();

        return $count;
    }
}