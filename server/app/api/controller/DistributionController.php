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

namespace app\api\controller;


use app\api\lists\DistributionOrderLists;
use app\api\lists\FansLists;
use app\api\logic\DistributionLogic;
use app\api\validate\DistributionValidate;
use think\exception\ValidateException;

class DistributionController extends BaseApiController
{
    /**
     * @notes 分销主页
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/25 6:59 下午
     */
    public function index()
    {
        $result = DistributionLogic::index($this->userId);
        return $this->data($result);
    }

    /**
     * @notes 分销等级列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/26 11:13 上午
     */
    public function levelLists()
    {
        $result = DistributionLogic::levelLists($this->userId);
        return $this->data($result);
    }

    /**
     * @notes 填写邀请码
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/26 12:00 下午
     */
    public function code()
    {
        $params = (new DistributionValidate())->post()->goCheck('code', ['user_id' => $this->userId]);
        $result = DistributionLogic::code($params);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功', [], 1, 1);
    }


    public function indexCode()
    {
        $params = $this->request->post();
        $params['user_id'] = $this->userId;
        try {
            validate(DistributionValidate::class)->scene('code')->check($params);
        } catch (ValidateException $err){
            return $this->success($err->getError());
        }

        $result = DistributionLogic::code($params);
        if(true !== $result) {
            return $this->success($result);
        }
        return $this->success('操作成功');
    }

    /**
     * @notes 分销申请
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/26 2:36 下午
     */
    public function apply()
    {
        $params = (new DistributionValidate())->post()->goCheck('apply', ['user_id' => $this->userId]);
        DistributionLogic::apply($params);
        return $this->success('操作成功', [], 1, 1);
    }

    /**
     * @notes 申请详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/26 3:12 下午
     */
    public function applyDetail()
    {
        $result = DistributionLogic::applyDetail($this->userId);
        return $this->data($result);
    }

    /**
     * @notes 粉丝列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/26 4:23 下午
     */
    public function fansLists()
    {
        return $this->dataLists(new FansLists());
    }

    /**
     * @notes 分销订单列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/10/26 5:20 下午
     */
    public function orderLists()
    {
        return $this->dataLists(new DistributionOrderLists());
    }
}