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

namespace app\adminapi\logic\recharge;

use app\common\enum\PayEnum;
use app\common\logic\BaseLogic;
use app\common\model\recharge\RechargeOrder;
use app\common\model\recharge\RechargeTemplate;
use app\common\service\ConfigService;
use app\common\service\FileService;
use think\Exception;
use think\facade\Db;

/**
 * 充值逻辑层
 * Class RechargeLogic
 * @package app\adminapi\logic\recharge
 */
class RechargeLogic extends BaseLogic
{

    /**
     * @notes 获取充值设置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2022/5/24 5:14 下午
     */
    public static function getRechargeConfig()
    {
        //充值设置
        $set = [
            'open' => ConfigService::get('recharge', 'open',0),
            'min_amount' => ConfigService::get('recharge', 'min_amount')
        ];
        //充值规则
        $rule = RechargeTemplate::field('id,money,award')->select()->toArray();

        return [
            'set' => $set,
            'rule' => $rule,
        ];
    }

    /**
     * @notes 充值设置
     * @param $params
     * @return bool
     * @author ljj
     * @date 2022/5/24 5:50 下午
     */
    public static function setRechargeConfig($params)
    {
        Db::startTrans();
        try {
            // 更新设置
            if(isset($params['open'])) {
                ConfigService::set('recharge', 'open', $params['open']);
            }
            if(isset($params['min_amount'])) {
                if($params['min_amount'] < 0){
                    throw new Exception('最低充值金额不能小于零');
                }
                ConfigService::set('recharge', 'min_amount', round($params['min_amount'],2));
            }
            // 更新规则
            // 清除旧数据
            $deleteIds = RechargeTemplate::column('id');
            RechargeTemplate::destroy($deleteIds);
            // 未设置充值规则,直接返回
            if(!isset($params['rule']) || empty($params['rule'])) {
                Db::commit();
                return true;
            }

            if (!is_array($params['rule'])) {
                throw new \Exception('充值规则格式不正确');
            }

            $data = [];

            foreach($params['rule'] as $key => $item) {
                if(!isset($item['money'])) {
                    throw new \think\Exception('规则' . ($key + 1) . '请输入充值金额');
                }
                if($item['money'] <= 0) {
                    throw new \think\Exception('规则' . ($key + 1) . '充值金额须大于0');
                }
                if(!isset($item['award'])) {
                    throw new \think\Exception('规则' . ($key + 1) . '请选择充值奖励');
                }
                if(!is_array($item['award']) || empty($item['award'])) {
                    throw new \think\Exception('规则' . ($key + 1) . '充值奖励格式错误或为空');
                }
                foreach($item['award'] as $subItem) {
                    if ($subItem['give_money'] < 0) {
                        throw new \think\Exception('规则' . ($key + 1) . '充值奖励不能为负数');
                    }
                }
                $data[] = [
                    'money' => $item['money'],
                    'award' => json_encode($item['award'], JSON_UNESCAPED_UNICODE),
                ];
            }
            (new RechargeTemplate())->saveAll($data);
            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }
}