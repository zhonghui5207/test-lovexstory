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
use app\adminapi\lists\distribution\DistributionApplyLists;
use app\adminapi\validate\distribution\DistributionApplyValidate;
use app\adminapi\logic\distribution\DistributionApplyLogic;


class DistributionApplyController extends BaseAdminController
{
    /**
     * @notes 分销申请列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 2:41 下午
     */
    public function lists()
    {
        return $this->dataLists(new DistributionApplyLists());
    }

    /**
     * @notes 分销申请详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 3:09 下午
     */
    public function detail()
    {
        $params = (new DistributionApplyValidate())->goCheck('detail');
        $result = DistributionApplyLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 分销申请审核
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 5:24 下午
     */
    public function audit()
    {
        $params = (new DistributionApplyValidate())->post()->goCheck('audit');
        $result = DistributionApplyLogic::audit($params);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功', [], 1, 1);
    }
}