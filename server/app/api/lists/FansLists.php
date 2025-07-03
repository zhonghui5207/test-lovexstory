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

namespace app\api\lists;

use app\common\model\user\User;

class FansLists extends BaseApiDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/26 4:07 下午
     */
    public function where()
    {
        $where = [];
        // 粉丝类型
        $type = $this->params['type'] ?? 'all';
        switch ($type) {
            // 一级
            case 'first':
                $where[] = ['first_leader', '=', $this->userId];
                break;
            // 二级
            case 'second':
                $where[] = ['second_leader', '=', $this->userId];
                break;
            // 默认
            default:
                $where[] = ['first_leader|second_leader', '=', $this->userId];
        }
        // 关键字搜索
        if (isset($this->params['keyword']) && !empty($this->params['keyword'])) {
            $where[] = ['nickname|mobile|account', 'like', '%' . $this->params['keyword'] .'%'];
        }

        return $where;
    }

    /**
     * @notes 自定义排序
     * @param $lists
     * @return mixed
     * @author ljj
     * @date 2023/10/26 4:18 下午
     */
    public function customizeOrder($lists)
    {
        // 粉丝数
        if(isset($this->params['fans_sort']) && !empty($this->params['fans_sort'])) {
            $sort = array_column($lists,'fans');
            array_multisort($sort,$this->params['fans_sort'] == 1 ? SORT_ASC : SORT_DESC,$lists);
        }
        // 已支付订单总金额
        if(isset($this->params['order_amount_sort']) && !empty($this->params['order_amount_sort'])) {
            $sort = array_column($lists,'order_amount');
            array_multisort($sort,$this->params['order_amount_sort'] == 1 ? SORT_ASC : SORT_DESC,$lists);
        }
        // 已支付订单数量
        if(isset($this->params['order_num_sort']) && !empty($this->params['order_num_sort'])) {
            $sort = array_column($lists,'order_num');
            array_multisort($sort,$this->params['order_num_sort'] == 1 ? SORT_ASC : SORT_DESC,$lists);
        }

        return $lists;
    }

    /**
     * @notes 粉丝列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/26 4:23 下午
     */
    public function lists(): array
    {
        $lists = User::field('id,avatar,nickname,mobile,account,create_time')
            ->where($this->where())
            ->append(['fans','order_amount','order_num'])
            ->order('id','desc')
            ->select()
            ->toArray();

        // 自定义排序
        $lists = $this->customizeOrder($lists);

        // 取指定页数据
        return array_slice($lists, $this->limitOffset, $this->limitLength);
    }

    /**
     * @notes 粉丝数量
     * @return int
     * @author ljj
     * @date 2023/10/26 4:23 下午
     */
    public function count(): int
    {
        return User::where($this->where())->count();
    }
}