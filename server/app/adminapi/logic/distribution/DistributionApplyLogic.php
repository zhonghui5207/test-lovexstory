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
use app\common\model\distribution\DistributionApply;
use app\common\model\distribution\DistributionLevel;
use app\common\service\RegionService;
use think\facade\Db;


class DistributionApplyLogic extends BaseLogic
{
    /**
     * @notes 分销申请详情
     * @param $params
     * @return mixed
     * @author ljj
     * @date 2023/10/23 3:09 下午
     */
    public static function detail($params)
    {
        $detail =DistributionApply::alias('da')
            ->leftJoin('user u', 'u.id = da.user_id')
            ->field('u.nickname,da.real_name,da.mobile,da.status,da.audit_remark,da.create_time,da.audit_time,da.province,da.city,da.district,da.reason')
            ->append(['status_desc'])
            ->findOrEmpty($params['id'])
            ->toArray();
        $detail['province_desc'] = RegionService::getAddress($detail['province']);
        $detail['city_desc'] = RegionService::getAddress($detail['city']);
        $detail['district_desc'] = RegionService::getAddress($detail['district']);

        return $detail;
    }

    /**
     * @notes 分销申请审核
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2023/10/23 5:23 下午
     */
    public static function audit($params)
    {
        Db::startTrans();
        try {
            // 更新【分销申请表】状态
            $distributionAplly = DistributionApply::where('id', $params['id'])->findOrEmpty();
            $distributionAplly->status = $params['status'];
            $distributionAplly->audit_time = time();
            if ($params['status'] == DistributionEnum::STATUS_REFUSE) {
                $distributionAplly->audit_remark = $params['audit_remark'];
            }
            $distributionAplly->save();

            // 审核通过，更新分销商信息
            if ($params['status'] == DistributionEnum::STATUS_PASS) {
                $distribution = Distribution::where('user_id', $distributionAplly['user_id'])->findOrEmpty();
                $default_level = DistributionLevel::where(['is_default'=>1])->findOrEmpty()->toArray();
                if ($distribution->isEmpty()) {
                    Distribution::create([
                        'user_id' => $distributionAplly['user_id'],
                        'level_id' => $default_level['id'],
                        'is_distribution' => YesNoEnum::YES,
                        'distribution_time' => time(),
                        'code' => createInvitationCode(),
                    ]);
                } else {
                    Distribution::where('user_id', $distributionAplly['user_id'])
                        ->update(['is_distribution' => YesNoEnum::YES, 'distribution_time' => time()]);
                }
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}