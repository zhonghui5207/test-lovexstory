<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\api\controller;


use app\api\lists\RechargeLists;
use app\api\logic\RechargeLogic;
use app\api\validate\RechargeValidate;

/**
 * 充值控制器类
 * Class RechargeController
 * @package app\api\controller
 */
class RechargeController extends BaseApiController
{
    /**
     * @notes 充值模板列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2022/6/9 11:03 上午
     */
    public function templateLists()
    {
        $result = (new RechargeLogic())->templateLists();
        return $this->success('',$result);
    }

    /**
     * @notes 充值
     * @return \think\response\Json
     * @author ljj
     * @date 2022/6/9 2:37 下午
     */
    public function recharge()
    {
        $params = $this->request->post();
        $params['user_id'] = $this->userId;
        $params['terminal'] = $this->userInfo['terminal'];
        $result = (new RechargeLogic())->recharge($params);
        if(is_array($result)){
            return $this->success('',$result);
        }
        return $this->fail($result);
    }

    /**
     * @notes 充值记录列表
     * @return \think\response\Json
     * @author ljj
     * @date 2022/6/9 3:13 下午
     */
    public function logLists()
    {
        return $this->dataLists(new RechargeLists());
    }
}