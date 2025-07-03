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
use app\adminapi\lists\distribution\DistributionCourseLists;
use app\adminapi\logic\distribution\DistributionCourseLogic;
use app\adminapi\validate\distribution\DistributionCourseValidate;

class DistributionCourseController extends BaseAdminController
{
    /**
     * @notes 分销课程列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/24 6:00 下午
     */
    public function lists()
    {
        return $this->dataLists(new DistributionCourseLists());
    }

    /**
     * @notes 分销课程详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/25 10:19 上午
     */
    public function detail()
    {
        $params = (new DistributionCourseValidate())->goCheck('detail');
        $result = DistributionCourseLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 设置佣金
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/25 10:43 上午
     */
    public function set()
    {
        $params = (new DistributionCourseValidate())->post()->goCheck('set');
        $result = DistributionCourseLogic::set($params);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功',[],1,1);

    }

    /**
     * @notes 参与/取消分销
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/25 10:53 上午
     */
    public function join()
    {
        $params = (new DistributionCourseValidate())->post()->goCheck('join');
        DistributionCourseLogic::join($params);
        return $this->success('操作成功', [], 1, 1);
    }
}
