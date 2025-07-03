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

namespace app\adminapi\logic\distribution;

use app\common\logic\BaseLogic;
use app\common\model\distribution\Distribution;
use app\common\model\user\User;
use think\facade\Db;


class DistributorLogic extends BaseLogic
{
    /**
     * @notes 分销商详情
     * @param $params
     * @return mixed
     * @author ljj
     * @date 2023/10/23 6:59 下午
     */
    public static function detail($params)
    {
        $distribution = Distribution::alias('d')
            ->Join('user u', 'u.id = d.user_id')
            ->field('d.id,u.avatar,u.nickname,u.account,u.sn as user_sn,d.level_id,u.first_leader,d.is_freeze,d.distribution_time,d.user_id,u.real_name')
            ->append(['level_name','earnings','wait_earnings','first_leader_name','is_freeze_desc','distribution_order_num','fans','fans_distribution','fans_one','fans_one_distribution','fans_two','fans_two_distribution'])
            ->where(['d.id'=>$params['id']])
            ->findOrEmpty()
            ->toArray();

        return $distribution;
    }

    /**
     * @notes 添加分销商
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/10/24 2:36 下午
     */
    public static function add($params)
    {
        foreach($params['user_ids'] as $user_id) {
            $distribution = Distribution::where(['user_id'=>$user_id])->findOrEmpty();
            if (!$distribution->isEmpty() && $distribution->is_distribution == 0) {
                $distribution->is_distribution = 1;
                $distribution->distribution_time = time();
                $distribution->save();
            }
            if ($distribution->isEmpty()) {
                Distribution::create([
                    'user_id' => $user_id,
                    'level_id' => $params['level_id'],
                    'is_distribution' => 1,
                    'distribution_time' => time(),
                    'code' => createInvitationCode(),
                ]);
            }
        }

        return true;
    }

    /**
     * @notes 冻结资格
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/10/24 2:41 下午
     */
    public static function freeze($params)
    {
        $distribution = Distribution::find($params['id']);
        $distribution->is_freeze = !$distribution->is_freeze;
        $distribution->save();
        return true;
    }

    /**
     * @notes 调整分销等级
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/24 2:54 下午
     */
    public static function adjustLevel($params)
    {
        $distribution = Distribution::find($params['id']);
        $distribution->level_id = $params['level_id'];
        $distribution->save();
        return true;
    }

    /**
     * @notes 调整上级分销商
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2023/10/24 4:00 下午
     */
    public static function adjustFirstLeader($params)
    {
        Db::startTrans();
        try {
            $formatData = [];
            switch($params['type']) {
                // 指定分销商
                case 1:
                    if ($params['first_leader'] ==  $params['user_id']) {
                        throw new \think\Exception('上级分销商不可以选择自己');
                    }
                    $firstLeader = User::field(['id','first_leader','second_leader'])
                        ->where('id', $params['first_leader'])
                        ->findOrEmpty()
                        ->toArray();
                    if(empty($firstLeader)) {
                        throw new \think\Exception('分销商不存在');
                    }
                    if(in_array($params['user_id'], [$firstLeader['first_leader'],$firstLeader['second_leader']])) {
                        throw new \think\Exception('不能设置自己的下级为上级分销商');
                    }

                    // 上级
                    $first_leader_id = $firstLeader['id'];
                    // 上上级
                    $second_leader_id = $firstLeader['first_leader'];
                    $formatData = [
                        'first_leader' => $first_leader_id,
                        'second_leader' => $second_leader_id,
                    ];
                    break;
                // 设置推荐人为系统,即清空上级
                case 2:
                    $formatData = [
                        'first_leader' => 0,
                        'second_leader' => null,
                    ];
                    break;
            }
            // 更新当前用户的分销关系
            User::where(['id' => $params['user_id']])->update($formatData);
            //更新当前用户下级的分销关系
            User::where(['first_leader' => $params['user_id']])->update(['second_leader'=>$formatData['first_leader']]);

            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}