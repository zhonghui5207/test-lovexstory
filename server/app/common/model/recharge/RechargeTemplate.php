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

namespace app\common\model\recharge;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class RechargeTemplate extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    /**
     * @notes 奖励获取器
     * @param $value
     * @return array|mixed
     * @author Tab
     * @date 2021/8/11 9:31
     */
    public function getAwardAttr($value)
    {
        if(empty($value)) {
            return [];
        }
        return json_decode($value, true);
    }

    /**
     * @notes 充值金额获取器
     * @param $value
     * @return int|mixed|string
     * @author Tab
     * @date 2021/8/11 10:18
     */
    public function getMoneyAttr($value)
    {
        return clear_zero($value);
    }
}