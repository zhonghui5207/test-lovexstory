<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\api\lists;


use app\common\enum\PayEnum;
use app\common\model\recharge\RechargeOrder;

class RechargeLists extends BaseApiDataLists
{
    /**
     * @notes 充值记录列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2022/6/9 3:13 下午
     */
    public function lists(): array
    {
        $lists = RechargeOrder::field('order_amount,award,create_time')
            ->where(['user_id' => $this->userId,'pay_status' => PayEnum::ISPAID])
            ->order('id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            if(empty($item['award']) || !is_array($item['award'])) {
                $item['tips'] = '充值' . clear_zero($item['order_amount']) . '元';
            }
            foreach($item['award'] as $subItem) {
                $item['tips'] = isset($subItem['give_money']) && $subItem['give_money'] > 0 ? '充' . clear_zero($item['order_amount']) . '送' . clear_zero($subItem['give_money']) . '元' : '';
                $item['order_amount'] += $subItem['give_money'];
            }
        }

        return $lists;
    }

    /**
     * @notes 充值记录数量
     * @return int
     * @author ljj
     * @date 2022/6/9 3:13 下午
     */
    public function count(): int
    {
        return RechargeOrder::where(['user_id' => $this->userId,'pay_status' => PayEnum::ISPAID])->count();
    }
}