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

use app\common\enum\DistributionEnum;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionOrder;
use app\common\model\user\User;

class DistributionDataLogic extends BaseLogic
{
    /**
     * @notes 分销概览
     * @return array[]
     * @author ljj
     * @date 2023/10/25 2:43 下午
     */
    public static function overview()
    {
        //今日入账佣金
        $data['today_earnings'] = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_SUCCESS])->whereDay('settlement_time')->sum('earnings');
        //今日新增待结算佣金
        $data['today_wait_earnings'] = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_WAIT])->whereDay('create_time')->sum('earnings');
        //待结算佣金
        $data['wait_earnings'] = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_WAIT])->sum('earnings');
        //累计已入账佣金
        $data['total_earnings'] = DistributionOrder::where(['status' => DistributionEnum::COMMISSION_STATUS_SUCCESS])->sum('earnings');
        //分销商
        $data['distributor'] = user::alias('u')
            ->join('distribution d', 'u.id = d.user_id')
            ->where(['is_distribution' => YesNoEnum::YES])->count();
        //分销商占比
        $user_num = User::count();
        $data['distributor_ratio'] = $user_num ? (round(($data['distributor'] / $user_num), 2) * 100) : 0;

        return $data;
    }

    /**
     * @notes 分销商收入排行榜
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/10/25 3:01 下午
     */
    public static function topDistributorEarnings($params)
    {
       $lists =  DistributionOrder::alias('do')
            ->leftJoin('user u', 'u.id = do.user_id')
            ->field('sum(do.earnings) as sum_earnings, do.user_id, u.avatar, u.nickname')
            ->where('do.status', DistributionEnum::COMMISSION_STATUS_SUCCESS)
            ->group('do.user_id')
            ->order('sum_earnings', 'desc')
            ->limit(50)
            ->page($params['page_no'], $params['page_size'])
            ->select()
            ->toArray();

        $count =  DistributionOrder::alias('do')
            ->leftJoin('user u', 'u.id = do.user_id')
            ->where('do.status', DistributionEnum::COMMISSION_STATUS_SUCCESS)
            ->group('do.user_id')
            ->limit(50)
            ->count();

        return [
            'lists' => $lists,
            'count' => $count,
            'page_no' => $params['page_no'],
            'page_size' => $params['page_size'],
        ];
    }

    /**
     * @notes 分销商下级人数排行榜
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/10/25 3:11 下午
     */
    public static function topDistributorFans($params)
    {
        $lists = Distribution::alias('d')
            ->Join('user u', 'u.id = d.user_id')
            ->field('u.avatar,u.nickname,d.user_id,d.id as fans')
            ->where('d.is_distribution', YesNoEnum::YES)
            ->select()
            ->toArray();

        // 获取排序列
        $sortColumn = array_column($lists, 'fans');
        // 根据下级人数排序
        array_multisort($sortColumn, SORT_DESC, SORT_NUMERIC, $lists);
        // 截取前50个
        $index = ($params['page_no'] - 1) * $params['page_size'];
        $newLists = array_slice($lists, $index, $params['page_size']);
        $count = count(array_slice($lists, 0, 50));

        return [
            'lists' => $newLists,
            'count' => $count,
            'page_no' => $params['page_no'],
            'page_size' => $params['page_size'],
        ];
    }
}