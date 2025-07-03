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

namespace app\api\lists;


use app\common\enum\OrderEnum;
use app\common\enum\QuestionbankEnum;
use app\common\model\order\Order;
use app\common\model\questionbank\Questionbank;
use app\common\model\questionbank\QuestionbankRecord;

class QuestionbankLists extends BaseApiDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/12/28 2:51 下午
     */
    public function where(): array
    {
        $where[] = ['category_id','=',$this->params['category_id']];
        $where[] = ['status','=',QuestionbankEnum::STATUS_ING];
        return $where;
    }

    /**
     * @notes 题库列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 3:19 下午
     */
    public function lists(): array
    {
        $lists = Questionbank::field('id,name,pay_type,pay_amount')
            ->append(['topic_num'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id'=>'asc'])
            ->select()
            ->toArray();

        foreach ($lists as $key=>$list){
            $lists[$key]['btn_status'] = 1;//按钮状态：1-开始答题；2-继续答题；3-查看报告；4-解锁答题；
            $record = QuestionbankRecord::where(['questionbank_id'=>$list['id'],'user_id'=>$this->userId])
                ->field('id,status,user_id')
                ->append(['finish_num'])
                ->order(['id'=>'desc'])
                ->findOrEmpty()->toArray();
            $lists[$key]['finish_num'] = $record['finish_num'] ?? 0;
            if (!empty($record)) {
                if ($record['finish_num'] > 0) {
                    $lists[$key]['btn_status'] = 2;
                }
                if ($record['status'] == QuestionbankEnum::RECORD_STATUS_FINISH) {
                    $lists[$key]['btn_status'] = 3;
                }
            }
            if ($list['pay_type'] == QuestionbankEnum::PAY_TYPE_CHARGE) {
                $order = Order::alias('o')
                    ->join('order_course oc','oc.order_id = o.id')
                    ->where(['oc.course_id'=>$list['id'],'o.user_id'=>$this->userId,'o.order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'o.refund_status'=>OrderEnum::REFUND_STATUS_NOT])
                    ->findOrEmpty();
                if ($order->isEmpty()) {
                    $lists[$key]['btn_status'] = 4;
                }
            }
        }

        return $lists;
    }

    /**
     * @notes 题库数量
     * @return int
     * @author ljj
     * @date 2023/12/28 3:20 下午
     */
    public function count(): int
    {
        return Questionbank::where($this->where())->count();
    }
}