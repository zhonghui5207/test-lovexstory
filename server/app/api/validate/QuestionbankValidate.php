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

namespace app\api\validate;


use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\enum\QuestionbankEnum;
use app\common\model\order\Order;
use app\common\model\questionbank\Questionbank;
use app\common\validate\BaseValidate;

class QuestionbankValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'topic_id' => 'require',
        'answer' => 'array',
    ];

    protected $message = [
        'id.require' => '请选择题库',
        'topic_id.require' => '请选择题目',
        'answer.require' => '请选择答案',
        'answer.array' => '答案值错误',
    ];

    public function sceneTopicLists()
    {
        return $this->only(['id'])
            ->append('id','checkTopicLists');
    }

    public function sceneAnswer()
    {
        return $this->only(['topic_id','answer']);
    }

    public function sceneSubmit()
    {
        return $this->only(['id']);
    }

    public function sceneReport()
    {
        return $this->only(['id']);
    }

    public function sceneAgain()
    {
        return $this->only(['id']);
    }

    public function sceneBuy()
    {
        return $this->only(['id'])
            ->append('id','checkBuy');
    }


    /**
     * @notes 校验题目列表
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     * @author ljj
     * @date 2023/12/28 3:55 下午
     */
    public function checkTopicLists($value,$rule,$data)
    {
        $questionbank = Questionbank::where(['id'=>$data['id']])->findOrEmpty()->toArray();
        if ($questionbank['pay_type'] == QuestionbankEnum::PAY_TYPE_CHARGE) {
            $order = Order::alias('o')
                ->join('order_course oc', 'oc.order_id = o.id')
                ->where(['oc.course_id'=>$data['id'],'o.pay_status'=>PayEnum::ISPAID,'refund_status'=>OrderEnum::REFUND_STATUS_NOT])
                ->findOrEmpty()
                ->toArray();
            if (empty($order)) {
                return '用户尚未购买该题库';
            }
        }

        return true;
    }

    /**
     * @notes 校验购买
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2024/1/5 3:52 下午
     */
    public function checkBuy($value,$rule,$data)
    {
        $order = Order::alias('o')
            ->join('order_course oc','oc.order_id = o.id')
            ->where(['oc.course_id'=>$data['id'],'o.user_id'=>$data['user_id'],'o.order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'o.refund_status'=>OrderEnum::REFUND_STATUS_NOT])
            ->findOrEmpty();
        if (!$order->isEmpty()) {
            return '已购买该题库';
        }
        return true;
    }
}