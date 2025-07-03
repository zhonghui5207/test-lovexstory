<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\adminapi\lists\finance;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\WithdrawEnum;
use app\common\lists\ListsExtendInterface;
use app\common\model\WithdrawApply;
use app\common\model\user\User;
use app\common\service\FileService;


class WithdrawLists extends BaseAdminDataLists implements ListsExtendInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2022/12/6 2:39 下午
     */
    public function where()
    {
        $where = [];
        if (isset($this->params['sn']) && $this->params['sn'] != '') {
            $where[] = ['wa.sn','like','%'.$this->params['sn'].'%'];
        }
        if (isset($this->params['user_info']) && $this->params['user_info'] != '') {
            $where[] = ['u.sn|u.mobile|u.nickname','like','%'.$this->params['user_info'].'%'];
        }
        if (isset($this->params['type']) && $this->params['type'] != '') {
            $where[] = ['wa.type','=',$this->params['type']];
        }
        // 开始时间
        if(isset($this->params['start_time']) && $this->params['start_time'] != '') {
            $where[] = ['wa.create_time', '>=', strtotime($this->params['start_time'])];
        }
        // 结束时间
        if(isset($this->params['end_time']) && $this->params['end_time'] != '') {
            $where[] = ['wa.create_time', '<=', strtotime($this->params['end_time'])];
        }
        if (isset($this->params['status']) && $this->params['status'] != '') {
            switch ($this->params['status']) {
                case 1:
                    $where[] = ['wa.status','=',WithdrawEnum::STATUS_WAIT];
                    break;
                case 2:
                    $where[] = ['wa.status','=',WithdrawEnum::STATUS_ING];
                    break;
                case 3:
                    $where[] = ['wa.status','=',WithdrawEnum::STATUS_SUCCESS];
                    break;
                case 4:
                    $where[] = ['wa.status','=',WithdrawEnum::STATUS_FAIL];
                    break;
            }
        }

        return $where;
    }

    /**
     * @notes 提现列表
     * @return array
     * @author ljj
     * @date 2022/12/6 2:43 下午
     */
    public function lists(): array
    {
        $lists = WithdrawApply::alias('wa')
            ->leftJoin('user u', 'u.id = wa.user_id')
            ->field('wa.id,wa.sn,wa.money,wa.left_money,wa.handling_fee,wa.type,wa.status,wa.apply_remark,wa.create_time,u.avatar,u.sn as user_sn,u.nickname,u.mobile')
            ->append(['type_desc','status_desc','verify_btn','transfer_btn'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order('wa.id', 'desc')
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            $item['avatar'] = FileService::getFileUrl($item['avatar']);
        }

        return $lists;
    }

    /**
     * @notes 提现数量
     * @return int
     * @author ljj
     * @date 2022/12/6 2:44 下午
     */
    public function count(): int
    {
        return WithdrawApply::alias('wa')
            ->leftJoin('user u', 'u.id = wa.user_id')
            ->where($this->where())
            ->count();
    }

    /**
     * @notes 统计数据
     * @return array
     * @author ljj
     * @date 2022/12/6 2:44 下午
     */
    public function extend()
    {
        $where = self::where();
        foreach ($where as $key=>$val) {
            if ($val[0] == 'wa.status') {
                unset($where[$key]);
            }
        }

        return [
            'all_count' => WithdrawApply::alias('wa')
                ->leftJoin('user u', 'u.id = wa.user_id')
                ->where($where)
                ->count(),
            'wait_count' => WithdrawApply::alias('wa')
                ->leftJoin('user u', 'u.id = wa.user_id')
                ->where($where)
                ->where('status', WithdrawEnum::STATUS_WAIT)
                ->count(),
            'ing_count' => WithdrawApply::alias('wa')
                ->leftJoin('user u', 'u.id = wa.user_id')
                ->where($where)
                ->where('status', WithdrawEnum::STATUS_ING)
                ->count(),
            'success_count' => WithdrawApply::alias('wa')
                ->leftJoin('user u', 'u.id = wa.user_id')
                ->where($where)
                ->where('status', WithdrawEnum::STATUS_SUCCESS)
                ->count(),
            'fail_count' => WithdrawApply::alias('wa')
                ->leftJoin('user u', 'u.id = wa.user_id')
                ->where($where)
                ->where('status', WithdrawEnum::STATUS_FAIL)
                ->count(),
        ];
    }
}