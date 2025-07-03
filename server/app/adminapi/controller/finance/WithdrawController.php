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

namespace app\adminapi\controller\finance;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\finance\WithdrawLists;
use app\adminapi\logic\finance\WithdrawLogic;
use app\adminapi\validate\finance\WithdrawValidate;


class WithdrawController extends BaseAdminController
{
    /**
     * @notes 提现列表
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/6 2:37 下午
     */
    public function lists()
    {
        return $this->dataLists(new WithdrawLists());
    }

    /**
     * @notes 提现详情
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/6 2:38 下午
     */
    public function detail()
    {
        $params = (new WithdrawValidate())->goCheck('detail');
        $result = WithdrawLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 审核拒绝
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/6 2:38 下午
     */
    public function refuse()
    {
        $params = (new WithdrawValidate())->post()->goCheck('refuse');
        $result = WithdrawLogic::refuse($params);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 审核通过
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/6 2:38 下午
     */
    public function pass()
    {
        $params = (new WithdrawValidate())->post()->goCheck('pass');
        $result = WithdrawLogic::pass($params);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 查询(提现至微信零钱)
     * @return \think\response\Json
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author ljj
     * @date 2022/12/6 2:38 下午
     */
    public function search()
    {
        $params = (new WithdrawValidate())->goCheck('search');
        $result = WithdrawLogic::search($params);
        if($result === false) {
            return $this->fail(WithdrawLogic::getError());
        }
        return $this->success($result,[],1,1);
    }

    /**
     * @notes 转账成功
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/6 2:38 下午
     */
    public function transferSuccess()
    {
        $params = (new WithdrawValidate())->post()->goCheck('transferSuccess');
        WithdrawLogic::transferSuccess($params);
        return $this->success('操作成功',[],1,1);
    }

    /**
     * @notes 转账失败
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/6 2:38 下午
     */
    public function transferFail()
    {
        $params = (new WithdrawValidate())->post()->goCheck('transferFail');
        $result = WithdrawLogic::transferFail($params);
        if(true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功',[],1,1);
    }
}
