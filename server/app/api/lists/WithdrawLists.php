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


use app\common\model\WithdrawApply;

class WithdrawLists extends BaseApiDataLists
{
    /**
     * @notes 提现申请列表
     * @return array
     * @author ljj
     * @date 2022/12/16 17:16
     */
    public function lists(): array
    {
        $lists = WithdrawApply::field('id,type,money,status,verify_remark,create_time')
            ->append(['type_desc','status_desc'])
            ->order('id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->where(['user_id'=>$this->userId])
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 提现申请数量
     * @return int
     * @author ljj
     * @date 2022/12/16 17:16
     */
    public function count(): int
    {
        return WithdrawApply::where(['user_id'=>$this->userId])->count();
    }
}