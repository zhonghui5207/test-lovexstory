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
use app\common\model\distribution\DistributionLevel;
use think\facade\Db;


class DistributionLevelLogic extends BaseLogic
{
    /**
     * @notes 添加分销等级
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/10/20 6:22 下午
     */
    public static function add($params)
    {
        DistributionLevel::create([
            'level' => $params['level'],
            'name' => $params['name'],
            'remark' => $params['remark'] ?? '',
            'level_ico' => $params['level_ico'],
            'level_bg' => $params['level_bg'],
            'self_ratio' => $params['self_ratio'],
            'first_ratio' => $params['first_ratio'],
            'second_ratio' => $params['second_ratio'],
            'upgrade_conditions' => $params['upgrade_conditions'],
            'single_amount' => !isset($params['single_amount']) || empty($params['single_amount']) ? null : $params['single_amount'],
            'total_amount' => !isset($params['total_amount']) || empty($params['total_amount']) ? null : $params['total_amount'],
            'consume_num' => !isset($params['consume_num']) || empty($params['consume_num']) ? null : $params['consume_num'],
            'settled_commission' => !isset($params['settled_commission']) || empty($params['settled_commission']) ? null : $params['settled_commission'],
        ]);

        return true;
    }

    /**
     * @notes 分销等级详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/10/23 10:16 上午
     */
    public static function detail($params)
    {
        return DistributionLevel::findOrEmpty($params['id'])->toArray();
    }

    /**
     * @notes 编辑分销等级
     * @param $params
     * @author ljj
     * @date 2023/10/23 10:40 上午
     */
    public static function edit($params)
    {
        $level = DistributionLevel::where('id',$params['id'])->findOrEmpty()->toArray();
        $data = [
            'name' => $params['name'],
            'remark' => $params['remark'] ?? '',
            'level_ico' => $params['level_ico'],
            'level_bg' => $params['level_bg'],
            'self_ratio' => $params['self_ratio'],
            'first_ratio' => $params['first_ratio'],
            'second_ratio' => $params['second_ratio'],
            'upgrade_conditions' => $params['upgrade_conditions'],
            'single_amount' => !isset($params['single_amount']) || empty($params['single_amount']) ? null : $params['single_amount'],
            'total_amount' => !isset($params['total_amount']) || empty($params['total_amount']) ? null : $params['total_amount'],
            'consume_num' => !isset($params['consume_num']) || empty($params['consume_num']) ? null : $params['consume_num'],
            'settled_commission' => !isset($params['settled_commission']) || empty($params['settled_commission']) ? null : $params['settled_commission'],
        ];
        if ($level['is_default'] == 0) {
            $data['level'] = $params['level'];
        }
        DistributionLevel::update($data,['id'=>$params['id']]);
        return true;
    }

    /**
     * @notes 删除分销等级
     * @param $params
     * @author ljj
     * @date 2023/10/23 10:43 上午
     */
    public static function del($params)
    {
        Db::startTrans();
        try {
            //删除分销等级
            DistributionLevel::destroy($params['id']);

            //该等级的分销商调整为默认等级
            $default_level = DistributionLevel::where(['is_default'=>1])->findOrEmpty()->toArray();
            Distribution::where('level_id', $params['id'])->update(['level_id' => $default_level['id']]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }


    /**
     * @notes 公共列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/23 6:09 下午
     */
    public static function commonLists()
    {
        $lists = DistributionLevel::field('id,level,name,self_ratio,first_ratio,second_ratio,is_default')
            ->append(['level_desc'])
            ->order('level', 'asc')
            ->select()
            ->toArray();

        return $lists;
    }
}