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
use app\adminapi\logic\distribution\DistributionDataLogic;

class DistributionDataController extends BaseAdminController
{
    /**
     * @notes 分销概览
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/25 2:48 下午
     */
    public function overview()
    {
        $result = DistributionDataLogic::overview();
        return $this->data($result);
    }

    /**
     * @notes 分销商收入排行榜
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/25 3:01 下午
     */
    public function topDistributorEarnings()
    {
        $params = request()->get();
        $params['page_no'] = isset($params['page_no']) && !empty($params['page_no']) ? (int)$params['page_no']: 1;
        $params['page_size'] = isset($params['page_size']) && !empty($params['page_size']) ? (int)$params['page_size']: 10;
        $params['page_size'] = $params['page_size'] > 50 ? 50 : $params['page_size']; // 限制每页最多显示50条
        $result = DistributionDataLogic::topDistributorEarnings($params);
        return $this->data($result);
    }

    /**
     * @notes 分销商下级人数排行榜
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/25 3:12 下午
     */
    public function topDistributorFans()
    {
        $params = request()->get();
        $params['page_no'] = isset($params['page_no']) && !empty($params['page_no']) ? (int)$params['page_no']: 1;
        $params['page_size'] = isset($params['page_size']) && !empty($params['page_size']) ? (int)$params['page_size']: 10;
        $params['page_size'] = $params['page_size'] > 50 ? 50 : $params['page_size']; // 限制每页最多显示50条
        $result = DistributionDataLogic::topDistributorFans($params);
        return $this->data($result);
    }
}