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

namespace app\common\command;


use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\model\order\Order;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\UserAuth;
use app\common\service\wechat\WeChatConfigService;
use DateTime;
use EasyWeChat\Factory;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use WpOrg\Requests\Requests;

class UploadShippingInfo extends Command
{
    protected function configure()
    {
        $this->setName('upload_shipping_info')
            ->setDescription('上传发货信息');
    }

    protected function execute(Input $input, Output $output)
    {
        $config = WeChatConfigService::getWechatConfigByTerminal(UserTerminalEnum::WECHAT_MMP);
        $app = Factory::miniProgram($config);
        $access_token = $app->access_token->getToken();
        $url = 'https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info?access_token='.$access_token['access_token'];

        $dateTime = new DateTime();

        $order_lists = Order::with(['order_course'])
            ->field('id,user_id,transaction_id')
            ->where(['is_upload_shipping'=>0,'order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'order_terminal'=>UserTerminalEnum::WECHAT_MMP,'pay_way'=>PayEnum::WECHAT_PAY])
            ->select()
            ->toArray();
        $recharge_lists = RechargeOrder::field('id,user_id,transaction_id')
            ->where(['is_upload_shipping'=>0,'terminal'=>UserTerminalEnum::WECHAT_MMP,'pay_status'=>PayEnum::ISPAID,'pay_way'=>PayEnum::WECHAT_PAY])
            ->select()
            ->toArray();
        $lists = array_merge_recursive($order_lists,$recharge_lists);
        $user_ids = array_unique(array_column($lists,'user_id'));
        $openid_arr = UserAuth::where(['user_id'=>$user_ids,'terminal'=>UserTerminalEnum::WECHAT_MMP])->column('openid','user_id');

        if (!empty($lists)) {
            $order_update_data = [];
            $recharge_update_data = [];
            $item_desc = '充值';
            $logistics_type = 3;
            foreach ($lists as $key=>$val) {
                $openid = $openid_arr[$val['user_id']] ?? null;
                if (!$openid) {
                    continue;
                }
                if (isset($val['order_course'])) {
                    $item_desc = $val['order_course']['course_snap']['name'] ?? '购买课程';
                }

                $formattedDateTime = $dateTime->setTimestamp(time())->format('Y-m-d\TH:i:s.vP');
                $data = [
                    'order_key' => [//订单，需要上传物流信息的订单
                        'order_number_type' => 2,//订单单号类型：1-使用下单商户号和商户侧单号；2-使用微信支付单号。
                        'transaction_id' => $val['transaction_id'],//原支付交易对应的微信订单号
                    ],
                    'logistics_type' => $logistics_type,//物流模式：1、实体物流配送采用快递公司进行实体物流配送形式 2、同城配送 3、虚拟商品，虚拟商品，例如话费充值，点卡等，无实体配送形式 4、用户自提
                    'delivery_mode' => 1,//发货模式枚举值：1、UNIFIED_DELIVERY（统一发货）2、SPLIT_DELIVERY（分拆发货）
                    'shipping_list' => [//物流信息列表
                        [
                            'tracking_no' => '',//物流单号，物流快递发货时必填
                            'express_company' => '',//物流公司编码，物流快递发货时必填
                            'item_desc' => mb_substr($item_desc,0,120),//商品信息,必填
                            'contact' => [//联系方式，当发货的物流公司为顺丰时，联系方式为必填
                                'receiver_contact' => '',//收件人联系方式
                            ],
                        ]
                    ],
                    'upload_time' => $formattedDateTime,//上传时间，用于标识请求的先后顺序
                    'payer' => [//支付者信息
                        'openid' => $openid,//用户标识
                    ],
                ];

                $response = Requests::post($url,[],json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                $result = json_decode($response->body, true);

                if (isset($val['order_course'])) {
                    $order_update_data[$key]['id'] = $val['id'];
                    $order_update_data[$key]['upload_shipping_result'] = $response->body;
                    if (isset($result['errcode']) && $result['errcode'] === 0) {
                        $order_update_data[$key]['is_upload_shipping'] = 1;
                    }
                } else {
                    $recharge_update_data[$key]['id'] = $val['id'];
                    $recharge_update_data[$key]['upload_shipping_result'] = $response->body;
                    if (isset($result['errcode']) && $result['errcode'] === 0) {
                        $recharge_update_data[$key]['is_upload_shipping'] = 1;
                    }
                }

                if (isset($result['errcode']) && $result['errcode'] === 40001) {
                    //重置token
                    $app->access_token->getRefreshedToken();
                    break;
                }
            }

            //更新订单信息
            (new Order())->saveAll($order_update_data);
            (new RechargeOrder())->saveAll($recharge_update_data);
        }
    }
}