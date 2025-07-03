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

namespace app\common\command;


use app\common\enum\marketing\CouponEnum;
use app\common\model\marketing\Coupon;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class CouponEnd extends Command
{

    protected function configure()
    {
        $this->setName('coupon_end')
            ->setDescription('优惠券活动结束');
    }

    protected function execute(Input $input, Output $output)
    {
        // 已过期的优惠券标记失效
        Coupon::where([['status', '=', CouponEnum::STATUS_ING], ['receive_time_end', '<=', time()]])->update(['status' => CouponEnum::STATUS_END]);

        return true;
    }
}