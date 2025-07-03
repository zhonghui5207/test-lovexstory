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
namespace app\common\model\user;
use app\common\enum\PayEnum;
use app\common\enum\UserTerminalEnum;
use app\common\model\BaseModel;
use app\common\model\distribution\Distribution;
use app\common\model\order\Order;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

/**
 * 用户模型类
 * Class User
 * @package app\common\model\user
 */
class User extends BaseModel
{

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    //关联用户授权模型
    public function userAuth()
    {
        return $this->hasOne(UserAuth::class, 'user_id');

    }

    public function getChannelAttr($value,$data){
        return UserTerminalEnum::getTermInalDesc($value);
    }

    /**
     * @notes 头像获取器 - 用于头像地址拼接域名
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/17 14:28
     */
    public function getAvatarAttr($value)
    {
        return trim($value) ? FileService::getFileUrl($value) : '';
    }

    public function getLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
    /**
     * @notes 性别转中文
     * @param $value
     * @return string
     * @author cjhao
     * @date 2022/5/24 16:14
     */
    public function getSexAttr($value)
    {
        switch ($value){
            case 1:
                $sex = '男';
                break;
            case 2:
                $sex = '女';
                break;
            default:
                $sex = '未知';
        }
        return $sex;
    }

    /**
     * @notes 生成用户编码
     * @param string $prefix
     * @param int $length
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 10:33
     */
    public static function createUserSn($prefix = '', $length = 8)
    {
        $rand_str = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_str .= mt_rand(0, 9);
        }
        $sn = $prefix . $rand_str;
        if (User::where(['sn' => $sn])->find()) {
            return self::createUserSn($prefix, $length);
        }
        return $sn;
    }

    /**
     * @notes 粉丝数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/26 4:22 下午]
     */
    public function getFansAttr($value,$data)
    {
        return User::where(['first_leader|second_leader'=>$data['id']])->count();
    }

    /**
     * @notes 订单总额
     * @param $value
     * @param $data
     * @return float
     * @author ljj
     * @date 2023/10/26 4:22 下午
     */
    public function getOrderAmountAttr($value,$data)
    {
        return Order::where(['user_id' => $data['id'], 'pay_status' => PayEnum::ISPAID])->sum('order_amount');
    }

    /**
     * @notes 订单总量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/10/26 4:22 下午
     */
    public function getOrderNumAttr($value,$data)
    {
        return Order::where(['user_id' => $data['id'], 'pay_status' => PayEnum::ISPAID])->count();
    }

    /**
     * @notes 上级分销商
     * @param $value
     * @param $data
     * @return mixed|string
     * @author ljj
     * @date 2023/10/30 2:22 下午
     */
    public function getFirstLeaderNameAttr($value,$data)
    {
        if ($data['first_leader'] === null) {
            return '-';
        }
        if ($data['first_leader'] == 0) {
            return '系统';
        }
        if ($data['first_leader'] > 0) {
            return User::where('id',$data['first_leader'])->value('nickname');
        }

        return '';
    }

    /**
     * @notes 是否是分销商
     * @param $value
     * @param $data
     * @return int|mixed
     * @author ljj
     * @date 2023/10/30 5:26 下午
     */
    public function getIsDistributorAttr($value,$data)
    {
        $is_distribution = Distribution::where(['user_id'=>$data['id']])->value('is_distribution') ?? 0;

        return $is_distribution;
    }
}