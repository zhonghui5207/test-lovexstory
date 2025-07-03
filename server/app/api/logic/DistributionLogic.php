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

namespace app\api\logic;

use app\common\enum\DistributionEnum;
use app\common\logic\BaseLogic;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionApply;
use app\common\model\distribution\DistributionLevel;
use app\common\model\distribution\DistributionOrder;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\RegionService;
use think\facade\Db;

class DistributionLogic extends BaseLogic
{
    /**
     * @notes 分销主页
     * @param $userId
     * @return array
     * @author ljj
     * @date 2023/10/25 6:59 下午
     */
    public static function index($userId)
    {
        // 是否为分销会员
        $distribution = Distribution::where('user_id', $userId)->field('is_distribution,level_id,code')->findOrEmpty()->toArray();
        if (empty($distribution)) {
            $distribution['is_distribution'] = 0;
            $distribution['level_id'] = 0;
            $distribution['code'] = '';
        }

        //当前用户信息
        $user = User::field('id,nickname,avatar,first_leader,user_earnings')->findOrEmpty($userId)->toArray();

        //上级分销商
        $first_leader_name = User::where('id',$user['first_leader'])->value('nickname');

        //用户分销等级信息
        $distribution_level = DistributionLevel::field('name,level_ico,level_bg')->where(['id'=>$distribution['level_id']])->findOrEmpty()->toArray();

        // 今天预估收益(未返佣金)
        $today_earnings = DistributionOrder::whereDay('create_time')->where(['user_id'=>$userId,'status'=>DistributionEnum::COMMISSION_STATUS_WAIT])->sum('earnings');

        // 本月预估收益(未返佣金)
        $month_earnings = DistributionOrder::whereMonth('create_time')->where(['user_id' => $userId, 'status' => DistributionEnum::COMMISSION_STATUS_WAIT])->sum('earnings');

        // 累计收益(已返佣金)
        $total_earnings = DistributionOrder::where(['user_id' => $userId, 'status' => DistributionEnum::COMMISSION_STATUS_SUCCESS])->sum('earnings');

        // 粉丝数(一级/二级)
        $fans = User::where(['first_leader|second_leader'=>$userId])->count();

        // 分销订单
        $distribution_order = DistributionOrder::where(['user_id'=>$userId])->count();

        //申请页配置
        $apply_image = ConfigService::get('distribution_config', 'apply_image', config('project.distribution_config.apply_image'));
        $apply_config['apply_image'] = empty($apply_image) ? FileService::getFileUrl(config('project.distribution_config.apply_image')) : FileService::getFileUrl($apply_image);
        //申请协议
        $apply_config['is_apply_protocol'] = ConfigService::get('distribution_config', 'is_apply_protocol', config('project.distribution_config.is_apply_protocol'));
        $apply_config['apply_protocol_content'] = ConfigService::get('distribution_config', 'apply_protocol_content');

        $data = [
            'is_distributon' => $distribution['is_distribution'],
            'code' => $distribution['code'],
            'user' => $user,
            'first_leader_name' => $first_leader_name,
            'distribution_level'  => $distribution_level,
            'today_earnings' => $today_earnings,
            'month_earnings' => $month_earnings,
            'total_earnings' => $total_earnings,
            'fans' => $fans,
            'distribution_order' => $distribution_order,
            'apply_config' => $apply_config,
        ];

        return $data;
    }

    /**
     * @notes 分销等级列表
     * @param $userId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/26 11:13 上午
     */
    public static function levelLists($userId)
    {
        //分销等级
        $distribution_level = DistributionLevel::field('id,level,name,remark,level_ico,level_bg,self_ratio,first_ratio,second_ratio,is_default,upgrade_conditions,single_amount,total_amount,consume_num,settled_commission')
            ->append(['upgrade_conditions_desc'])
            ->order(['level'=>'asc'])
            ->select()
            ->toArray();

        //单笔消费金额
        $single_amount = DistributionOrder::alias('do')
            ->join('order_course oc', 'oc.id = do.order_course_id')
            ->join('order o', 'o.id = oc.order_id')
            ->where(['do.user_id'=>$userId])
            ->order(['o.order_amount'=>'desc'])
            ->value('o.order_amount') ?? 0;
        //累计消费金额
        $total_amount = DistributionOrder::alias('do')
            ->join('order_course oc', 'oc.id = do.order_course_id')
            ->join('order o', 'o.id = oc.order_id')
            ->where(['do.user_id'=>$userId])
            ->sum('o.order_amount');
        //累计消费次数
        $consume_num = DistributionOrder::alias('do')
            ->join('order_course oc', 'oc.id = do.order_course_id')
            ->join('order o', 'o.id = oc.order_id')
            ->where(['do.user_id'=>$userId])
            ->count('o.id');
        //已结算佣金收入
        $settled_commission = DistributionOrder::where(['user_id' => $userId, 'status' => DistributionEnum::COMMISSION_STATUS_SUCCESS])->sum('earnings');

        //用户分销等级
        $user_level = DistributionLevel::alias('dl')
            ->join('distribution d', 'd.level_id = dl.id')
            ->field('dl.id,dl.level')
            ->where(['d.user_id'=>$userId,'is_distribution'=>1])
            ->findOrEmpty()
            ->toArray();

        foreach ($distribution_level as &$level) {
            //单笔消费金额
            $level['single_amount_completed'] = 0;
            $level['single_amount_completed_num'] = 0;
            if ($single_amount >= $level['single_amount']) {
                $level['single_amount_completed'] = 1;
                $level['single_amount_completed_num'] = 1;
            }
            $level['single_amount_show'] = 0;
            if ($level['single_amount'] !== null) {
                $level['single_amount_show'] = 1;
            }
            //累计消费金额
            $level['total_amount_completed'] = 0;
            $level['total_amount_completed_num'] = $total_amount;
            if ($total_amount >= $level['total_amount']) {
                $level['total_amount_completed'] = 1;
            }
            $level['total_amount_show'] = 0;
            if ($level['total_amount'] !== null) {
                $level['total_amount_show'] = 1;
            }
            //累计消费次数
            $level['consume_num_completed'] = 0;
            $level['consume_num_completed_num'] = $consume_num;
            if ($consume_num >= $level['consume_num']) {
                $level['consume_num_completed'] = 1;
            }
            $level['consume_num_show'] = 0;
            if ($level['consume_num'] !== null) {
                $level['consume_num_show'] = 1;
            }
            //已结算佣金收入
            $level['settled_commission_completed'] = 0;
            $level['settled_commission_completed_num'] = $settled_commission;
            if ($settled_commission >= $level['settled_commission']) {
                $level['settled_commission_completed'] = 1;
            }
            $level['settled_commission_show'] = 0;
            if ($level['settled_commission'] !== null) {
                $level['settled_commission_show'] = 1;
            }

            //等级状态：0-未达成；1-已达成；2-处于当前等级；
            $level['is_level'] = 0;
            if (!empty($user_level)) {
                if ($user_level['level'] >= $level['level']) {
                    $level['is_level'] = 1;
                }
                if ($user_level['id'] >= $level['id']) {
                    $level['is_level'] = 2;
                }
            }
        }

        return $distribution_level;
    }

    /**
     * @notes 填写邀请码
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2023/10/26 11:59 上午
     */
    public static function code($params)
    {
        Db::startTrans();
        try{
            $firstLeader = User::alias('u')
                ->leftJoin('distribution d', 'd.user_id = u.id')
                ->field('u.id,u.first_leader,u.second_leader')
                ->where('d.code', $params['code'])
                ->findOrEmpty();

            // 上级
            $firstLeaderId = $firstLeader['id'];
            // 上上级
            $secondLeaderId = $firstLeader['first_leader'];
            $data = [
                'id' => $params['user_id'],
                'first_leader' => $firstLeaderId,
                'second_leader' => $secondLeaderId,
            ];
            // 更新当前用户的分销关系
            User::update($data);

            //更新当前用户下级的分销关系
            User::where('first_leader', $params['user_id'])->update(['second_leader' => $firstLeaderId]);

            Db::commit();
            return true;
        }catch(\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 分销申请
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/10/26 2:36 下午
     */
    public static function apply($params)
    {
        DistributionApply::create([
            'user_id' => $params['user_id'],
            'real_name' => $params['real_name'],
            'mobile' => $params['mobile'],
            'province' => $params['province'],
            'city' => $params['city'],
            'district' => $params['district'],
            'reason' => $params['reason'],
        ]);

        return true;
    }

    /**
     * @notes 申请详情
     * @param $userId
     * @return array
     * @author ljj
     * @date 2023/10/26 3:11 下午
     */
    public static function applyDetail($userId)
    {
        $applyDetail = DistributionApply::where('user_id', $userId)->order('id', 'desc')->findOrEmpty();
        if($applyDetail->isEmpty()) {
            return [];
        }

        $applyDetail = $applyDetail->toArray();
        $applyDetail['province_desc'] = RegionService::getAddress($applyDetail['province']);
        $applyDetail['city_desc'] = RegionService::getAddress($applyDetail['city']);
        $applyDetail['district_desc'] = RegionService::getAddress($applyDetail['district']);
        $applyDetail['status_desc'] = DistributionEnum::getStatusDesc($applyDetail['status']);

        return $applyDetail;
    }
}