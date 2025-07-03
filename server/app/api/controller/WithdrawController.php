<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\api\lists\WithdrawLists;
use app\api\logic\WithdrawLogic;
use app\api\validate\WithdrawValidate;


class WithdrawController extends BaseApiController
{
    /**
     * @notes 获取提现配置
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/16 16:45
     */
    public function getConfig()
    {
        $result = WithdrawLogic::getConfig($this->userId,$this->userInfo['terminal']);
        return $this->data($result);
    }

    /**
     * @notes 提现申请
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/16 16:53
     */
    public function apply()
    {
        $params = (new WithdrawValidate())->post()->goCheck('apply', ['user_id' => $this->userId]);
        $params['user_id'] = $this->userId;
        $result = WithdrawLogic::apply($params);
        if($result !== false) {
            return $this->success('提现申请成功', ['id' => $result]);
        }
        return $this->fail(WithdrawLogic::getError());
    }

    /**
     * @notes 提现申请列表
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/16 17:15
     */
    public function lists()
    {
        return $this->dataLists(new WithdrawLists());
    }

    /**
     * @notes 提现申请详情
     * @return \think\response\Json
     * @author ljj
     * @date 2022/12/16 17:25
     */
    public function detail()
    {
        $params = (new WithdrawValidate())->goCheck('detail');
        $result = WithdrawLogic::detail($params);
        return $this->data($result);
    }
}