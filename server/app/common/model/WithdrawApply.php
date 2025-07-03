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

namespace app\common\model;


use app\common\enum\WithdrawEnum;
use app\common\model\user\User;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

class WithdrawApply extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 关联用户模型
     * @return \think\model\relation\HasOne
     * @author ljj
     * @date 2022/12/6 3:12 下午
     */
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }


    /**
     * @notes 类型
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2022/12/2 5:11 下午
     */
    public function getTypeDescAttr($value,$data)
    {
        return WithdrawEnum::getTypeDesc($data['type']);
    }

    /**
     * @notes 状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2022/12/2 5:11 下午
     */
    public function getStatusDescAttr($value,$data)
    {
        return WithdrawEnum::getStatusDesc($data['status']);
    }

    /**
     * @notes 转账凭证
     * @param $value
     * @return false|string
     * @author ljj
     * @date 2022/12/2 5:11 下午
     */
    public function getTransferVoucherAttr($value)
    {
        return empty($value) ? '' : FileService::getFileUrl($value);
    }

    /**
     * @notes 转账时间
     * @param $value
     * @return false|string
     * @author ljj
     * @date 2022/12/2 5:12 下午
     */
    public function getTransferTimeAttr($value)
    {
        return empty($value) ? '' : date('Y-m-d H:i:s',$value);
    }

    /**
     * @notes 审核时间
     * @param $value
     * @return false|string
     * @author ljj
     * @date 2022/12/6 3:25 下午
     */
    public function getVerifyTimeAttr($value)
    {
        return empty($value) ? '' : date('Y-m-d H:i:s',$value);
    }

    /**
     * @notes 审核按钮
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2022/12/6 5:16 下午
     */
    public function getVerifyBtnAttr($value,$data)
    {
        $btn = 0;
        $result = WithdrawApply::findOrEmpty($data['id']);
        if($result->status == WithdrawEnum::STATUS_WAIT) {
            $btn = 1;
        }

        return $btn;
    }

    /**
     * @notes 转账按钮
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2022/12/6 5:17 下午
     */
    public function getTransferBtnAttr($value,$data)
    {
        $btn = 0;
        $result = WithdrawApply::findOrEmpty($data['id']);
        if($result->status == WithdrawEnum::STATUS_ING && ($result->type == WithdrawEnum::TYPE_BANK || $result->type == WithdrawEnum::TYPE_WECHAT_CODE || $result->type == WithdrawEnum::TYPE_ALI_CODE)) {
            $btn = 1;
        }

        return $btn;
    }
}