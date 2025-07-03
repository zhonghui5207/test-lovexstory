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

namespace app\api\logic;


use app\common\enum\PayEnum;
use app\common\logic\BaseLogic;
use app\common\model\recharge\RechargeOrder;
use app\common\model\recharge\RechargeTemplate;
use app\common\service\ConfigService;
use think\Exception;

/**
 * 充值逻辑类
 * Class RechargeLogic
 * @package app\api\logic
 */
class RechargeLogic extends BaseLogic
{
    /**
     * @notes 充值模板列表
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2022/6/9 11:03 上午
     */
    public function templateLists()
    {
        $lists = RechargeTemplate::field('id,money,award')->select()->toArray();
        foreach($lists as &$item) {
            if(empty($item['award']) || !is_array($item['award'])) {
                $item['tips'] = '';
                continue;
            }
            foreach($item['award'] as $subItem) {
                $item['tips'] = isset($subItem['give_money']) && $subItem['give_money'] > 0 ? '充' . $item['money'] . '送' . clear_zero($subItem['give_money']) . '元' : '';
            }
        }

        return $lists;
    }

    /**
     * @notes 充值
     * @param $params
     * @return array
     * @author ljj
     * @date 2022/6/9 2:37 下午
     */
    public function recharge(array $params)
    {
        try {

            $templateId = $params['template_id'] ?? '';
            $money = $params['money'] ?? '';
            if('' == $templateId && '' == $money){
                throw new Exception('缺少参数');
            }
            $open = ConfigService::get('recharge', 'open',0);
            if(!$open) {
                throw new Exception('充值功能已关闭');
            }
            $award = '';
            if($templateId){
                //选择模板充值
                $template = RechargeTemplate::findOrEmpty($templateId);
                if($template->isEmpty()) {
                    throw new Exception('充值模板不存在');
                }
                $award = empty($template['award']) ? '' : json_encode($template['award'], JSON_UNESCAPED_UNICODE);
                $rechargeMoney = $template['money'];
            }else{
                //自定义金额充值
                if( $money <= 0){
                    throw new Exception('充值金额错误');
                }
                $minAmount = ConfigService::get('recharge', 'min_amount');
                if($money < $minAmount) {
                    throw new Exception('最低充值金额:'.$minAmount.'元');
                }
                $rechargeMoney = $money;
            }

            $order = RechargeOrder::create([
                'sn'            => generate_sn((new RechargeOrder()),'sn'),
                'user_id'       => $params['user_id'],
                'terminal'      => $params['terminal'],
                'pay_status'    => PayEnum::UNPAID,
                'order_amount'  => $rechargeMoney,
                'template_id'   => $params['template_id'] ?? 0,
                'award'         => $award
            ]);
            return [
                'order_id' => $order->id,
                'from' => 'recharge'
            ];

        }catch (Exception $e){

            return $e->getMessage();
        }


    }
}