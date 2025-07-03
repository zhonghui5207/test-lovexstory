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
use app\adminapi\lists\distribution\DistributorLists;
use app\adminapi\lists\distribution\FansLists;
use app\adminapi\logic\distribution\DistributorLogic;
use app\adminapi\validate\distribution\DistributorValidate;


class DistributorController extends BaseAdminController
{
    /**
     * @notes 分销商列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 6:29 下午
     */
    public function lists()
    {
        return $this->dataLists(new DistributorLists());
    }

    /**
     * @notes 分销商详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 7:00 下午
     */
    public function detail()
    {
        $params = (new DistributorValidate())->goCheck('detail');
        $result = DistributorLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 添加分销商
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/24 2:36 下午
     */
    public function add()
    {
        $params = (new DistributorValidate())->post()->goCheck('add');
        DistributorLogic::add($params);
        return $this->success('操作成功', [], 1, 1);
    }

    /**
     * @notes 冻结资格
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/24 2:41 下午
     */
    public function freeze()
    {
        $params = (new DistributorValidate())->post()->goCheck('freeze');
        DistributorLogic::freeze($params);
        return $this->success('操作成功', [], 1, 1);
    }

    /**
     * @notes 调整分销等级
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/24 2:55 下午
     */
    public function adjustLevel()
    {
        $params = (new DistributorValidate())->post()->goCheck('adjustLevel');
        DistributorLogic::adjustLevel($params);
        return $this->success('操作成功', [], 1, 1);
    }

    /**
     * @notes 调整上级分销商
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/24 4:00 下午
     */
    public function adjustFirstLeader()
    {
        $params = (new DistributorValidate())->post()->goCheck('adjustFirstLeader');
        $result = DistributorLogic::adjustFirstLeader($params);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('调整成功', [], 1, 1);
    }

    /**
     * @notes 粉丝列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/24 4:41 下午
     */
    public function fansLists()
    {
        return $this->dataLists(new FansLists());
    }
}