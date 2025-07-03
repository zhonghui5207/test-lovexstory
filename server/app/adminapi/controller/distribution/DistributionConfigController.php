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

namespace app\adminapi\controller\distribution;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\distribution\DistributionConfigLogic;
use app\adminapi\validate\distribution\DistributionConfigValidate;


class DistributionConfigController extends BaseAdminController
{
    /**
     * @notes 获取基础设置
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/20 4:01 下午
     */
    public function getConfig()
    {
        $result = DistributionConfigLogic::getConfig();
        return $this->data($result);
    }

    /**
     * @notes 设置基础设置
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/20 4:22 下午
     */
    public function setConfig()
    {
        $params = (new DistributionConfigValidate())->post()->goCheck('setConfig');
        DistributionConfigLogic::setConfig($params);
        return $this->success('设置成功',[],1,1);
    }

    /**
     * @notes 获取结算设置
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/20 4:57 下午
     */
    public function getSettleConfig()
    {
        $result = DistributionConfigLogic::getSettleConfig();
        return $this->data($result);
    }

    /**
     * @notes 设置结算设置
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/20 5:01 下午
     */
    public function setSettleConfig()
    {
        $params = (new DistributionConfigValidate())->post()->goCheck('setSettleConfig');
        DistributionConfigLogic::setSettleConfig($params);
        return $this->success('设置成功',[],1,1);
    }
}