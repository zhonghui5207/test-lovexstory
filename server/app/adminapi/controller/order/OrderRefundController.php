<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\controller\order;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\order\OrderRefundLists;
use app\adminapi\lists\order\OrderRefundLogLists;
use app\adminapi\logic\order\OrderLogic;
use app\adminapi\logic\order\OrderRefundLogic;
use app\adminapi\validate\order\OrderRefundValidate;

class OrderRefundController extends BaseAdminController
{

    /**
     * @notes 获取其他列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/10/31 18:50
     */
    public function otherLists()
    {
        $otherLists = (new OrderRefundLogic())->otherLists();
        return $this->success('',$otherLists);
    }
    /**
     * @notes 订单退款列表
     * @return \think\response\Json
     * @author ljj
     * @date 2022/9/9 4:53 下午
     */
    public function lists()
    {
        return $this->dataLists(new OrderRefundLists());
    }

    /**
     * @notes 退款日志列表
     * @return \think\response\Json
     * @author ljj
     * @date 2022/9/9 5:53 下午
     */
    public function logLists()
    {
        return $this->dataLists(new OrderRefundLogLists());
    }

    /**
     * @notes 重新退款
     * @return \think\response\Json
     * @author ljj
     * @date 2022/9/9 6:19 下午
     */
    public function reRefund()
    {
        $params = (new OrderRefundValidate())->post()->goCheck('reRefund');
        $params['admin_id'] = $this->adminId;
        $result = (new OrderRefundLogic())->reRefund($params);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功',[],1,1);
    }
}