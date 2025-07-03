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
use app\adminapi\lists\distribution\DistributionLevelLists;
use app\adminapi\logic\distribution\DistributionLevelLogic;
use app\adminapi\validate\distribution\DistributionLevelValidate;


class DistributionLevelController extends BaseAdminController
{
    /**
     * @notes 分销等级列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/20 6:02 下午
     */
    public function lists()
    {
        return $this->dataLists(new DistributionLevelLists());
    }

    /**
     * @notes 添加分销等级
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/20 6:22 下午
     */
    public function add()
    {
        $params = (new DistributionLevelValidate())->post()->goCheck('add');
        DistributionLevelLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }

    /**
     * @notes 分销等级详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 10:16 上午
     */
    public function detail()
    {
        $params = (new DistributionLevelValidate())->goCheck('detail');
        $result = DistributionLevelLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 编辑分销等级
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 10:41 上午
     */
    public function edit()
    {
        $params = (new DistributionLevelValidate())->post()->goCheck('edit');
        DistributionLevelLogic::edit($params);
        return $this->success('编辑成功', [], 1, 1);
    }

    /**
     * @notes 删除分销等级
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/23 10:44 上午
     */
    public function del()
    {
        $params = (new DistributionLevelValidate())->post()->goCheck('del');
        $result = DistributionLevelLogic::del($params);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('删除成功', [], 1, 1);
    }

    /**
     * @notes 公共列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/23 6:09 下午
     */
    public function commonLists()
    {
        $result = DistributionLevelLogic::commonLists();
        return $this->data($result);
    }
}