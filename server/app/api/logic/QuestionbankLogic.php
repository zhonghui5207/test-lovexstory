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


use app\common\enum\CourseEnum;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\enum\QuestionbankEnum;
use app\common\logic\BaseLogic;
use app\common\logic\OrderLogLogic;
use app\common\model\course\Course;
use app\common\model\order\Order;
use app\common\model\questionbank\Questionbank;
use app\common\model\questionbank\QuestionbankCategory;
use app\common\model\questionbank\QuestionbankRecord;
use app\common\model\questionbank\QuestionbankRecordLog;
use app\common\model\questionbank\QuestionbankTopic;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;
use think\facade\Db;

class QuestionbankLogic extends BaseLogic
{
    /**
     * @notes 题库分类列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 2:45 下午
     */
    public function categoryLists()
    {
        $lists = QuestionbankCategory::field('id,name')
            ->order(['sort'=>'desc','id'=>'asc'])
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 题目列表
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 5:22 下午
     */
    public function topicLists($params)
    {
        $topicLists = QuestionbankTopic::where(['questionbank_id'=>$params['id']])
            ->field('id,type,stem,illustration,option,answer,analysis,difficulty,points')
            ->json(['option','answer'],true)
            ->order(['id'=>'asc'])
            ->select()
            ->toArray();

        $record = QuestionbankRecord::field('id')
            ->with(['record_log'])
            ->where(['questionbank_id'=>$params['id'],'user_id'=>$params['user_id']])
            ->order(['id'=>'desc'])
            ->findOrEmpty()
            ->toArray();

        $record_log = array_column($record['record_log'] ?? [],null,'topic_id');
        $record_log_update_time = array_column($record['record_log'] ?? [],null,'update_time');
        $last_time = empty($record_log_update_time) ? [] : max($record_log_update_time);
        foreach ($topicLists as $key=>$topic) {
            $topicLists[$key]['type_desc'] = QuestionbankEnum::getTopicTypeDesc($topic['type']);
            $log = $record_log[$topic['id']] ?? [];
            if (!empty($log)) {
                $topicLists[$key]['user_answer'] = $log['user_answer'];
                $topicLists[$key]['is_true'] = $log['is_true'];

                if (isset($last_time['id']) && $last_time['id'] == $topic['id']) {
                    $topicLists[$key]['is_here'] = true;
                }
            } elseif ($key == 0) {
                $topicLists[$key]['is_here'] = true;
            }
        }
        return $topicLists;
    }

    /**
     * @notes 提交答案
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2023/12/28 5:52 下午
     */
    public function answer($params)
    {
        Db::startTrans();
        try {
            $topic = QuestionbankTopic::where(['id'=>$params['topic_id']])->json(['answer'],true)->findOrEmpty()->toArray();

            $record = QuestionbankRecord::where(['questionbank_id'=>$topic['questionbank_id'],'user_id'=>$params['user_id'],'status'=>QuestionbankEnum::RECORD_STATUS_NOT])
                ->order(['id'=>'desc'])
                ->findOrEmpty();
            if ($record->isEmpty()) {
                $record = QuestionbankRecord::create([
                    'questionbank_id' => $topic['questionbank_id'],
                    'user_id' => $params['user_id'],
                ]);
            }

            $record_log = QuestionbankRecordLog::where(['record_id'=>$record->id,'user_id'=>$params['user_id'],'topic_id'=>$params['topic_id']])->findOrEmpty();
            if (isset($params['answer']) && !empty($params['answer'])) {
                //判断答案是否正确
                $isTrue = 0;
                if ($params['answer'] == $topic['answer']) {
                    $isTrue = 1;
                }
                if ($record_log->isEmpty()) {
                    QuestionbankRecordLog::create([
                        'record_id' => $record->id,
                        'user_id' => $params['user_id'],
                        'topic_id' => $params['topic_id'],
                        'user_answer' => json_encode($params['answer']),
                        'is_true' => $isTrue,
                    ]);
                } else {
                    QuestionbankRecordLog::update([
                        'user_answer' => json_encode($params['answer']),
                        'is_true' => $isTrue,
                    ],['id'=>$record_log->id]);
                }
            } else {
                if (!$record_log->isEmpty()) {
                    QuestionbankRecordLog::destroy($record_log->id);
                }
            }


            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $e->getMessage();
        }
    }


    /**
     * @notes 交卷
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2023/12/28 6:12 下午
     */
    public function submit($params)
    {
        Db::startTrans();
        try {
            $record = QuestionbankRecord::where(['questionbank_id'=>$params['id'],'user_id'=>$params['user_id'],'status'=>QuestionbankEnum::RECORD_STATUS_NOT])->findOrEmpty();
            if ($record->isEmpty()) {
                throw new \think\Exception('参数错误');
            }
            $record->status = QuestionbankEnum::RECORD_STATUS_FINISH;
            $record->save();

            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 刷题报告
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 6:36 下午
     */
    public function report($params)
    {
        $topicLists = QuestionbankTopic::where(['questionbank_id'=>$params['id']])
            ->field('id,type')
            ->append(['type_desc'])
            ->select()
            ->toArray();
        $record = QuestionbankRecord::field('id')
            ->with(['record_log'])
            ->where(['questionbank_id'=>$params['id'],'user_id'=>$params['user_id']])
            ->order(['id'=>'desc'])
            ->findOrEmpty()
            ->toArray();

        $result = [];
        $result['questionbank_name'] = Questionbank::where(['id'=>$params['id']])->value('name');
        $result['user_image'] = User::where(['id'=>$params['user_id']])->value('avatar');
        $result['user_image'] = empty($result['user_image']) ? '' : FileService::getFileUrl($result['user_image']);
        $result['right_num'] = 0;
        $result['error_num'] = 0;
        $result['not_num'] = 0;
        $result['topic_lists'] = [];

        $getTopicTypeDesc = QuestionbankEnum::getTopicTypeDesc(true);
        foreach ($getTopicTypeDesc as $type) {
            $result['topic_lists'][$type] = [];
        }
        $record_log = array_column($record['record_log'] ?? [],null,'topic_id');
        foreach ($topicLists as $key=>$topic) {
            $status = 1;//状态：1-未答；2-正确；3-错误；
            $log = $record_log[$topic['id']] ?? [];
            if (!empty($log)) {
                if ($log['is_true'] == 1) {
                    $status = 2;
                    $result['right_num'] += 1;
                } else {
                    $status = 3;
                    $result['error_num'] += 1;
                }
            } else {
                $result['not_num'] += 1;
            }

            $result['topic_lists'][$topic['type_desc']][] = [
                'id' => $topic['id'],
                'index' => ($key + 1),
                'status' => $status,
            ];
        }
        foreach ($getTopicTypeDesc as $type) {
            if (empty($result['topic_lists'][$type])) {
                unset($result['topic_lists'][$type]);
            }
        }

        return $result;
    }

    /**
     * @notes 我的题库
     * @param $params
     * @return array
     * @author ljj
     * @date 2024/1/2 9:48 上午
     */
    public function userQuestionbankLists($params)
    {
        $lists = QuestionbankRecord::alias('qr')
            ->join('questionbank q', 'q.id = qr.questionbank_id')
            ->field('qr.id,q.name as questionbank_name,qr.status,q.id as questionbank_id,qr.user_id,q.pay_type,q.pay_amount')
            ->append(['finish_num','topic_num'])
            ->whereNull('q.delete_time')
            ->where(['qr.user_id'=>$params['user_id']])
            ->order(['qr.id'=>'asc'])
            ->select()
            ->toArray();

        $result = [];
        foreach ($lists as $key=>$list){
            //状态：1-未开始；2-进行中；3-已完成；
            if (isset($params['status']) && $params['status'] != '') {
                if ($params['status'] == 1 && $list['finish_num'] > 0) {
                    continue;
                }
                if ($params['status'] == 2 && ($list['finish_num'] <= 0 || $list['status'] == QuestionbankEnum::RECORD_STATUS_FINISH)) {
                    continue;
                }
                if ($params['status'] == 3 && $list['status'] != QuestionbankEnum::RECORD_STATUS_FINISH) {
                    continue;
                }
            }

            $result[$list['questionbank_id']]['id'] = $list['questionbank_id'];
            $result[$list['questionbank_id']]['name'] = $list['questionbank_name'];
            $result[$list['questionbank_id']]['pay_amount'] = $list['pay_amount'];
            $result[$list['questionbank_id']]['finish_num'] = $list['finish_num'];
            $result[$list['questionbank_id']]['topic_num'] = $list['topic_num'];
            //按钮状态：1-开始答题；2-继续答题；3-查看报告；4-解锁答题；
            $result[$list['questionbank_id']]['btn_status'] = 1;
            if ($list['finish_num'] > 0) {
                $result[$list['questionbank_id']]['btn_status'] = 2;
            }
            if ($list['status'] == QuestionbankEnum::RECORD_STATUS_FINISH) {
                $result[$list['questionbank_id']]['btn_status'] = 3;
            }
            if ($list['pay_type'] == QuestionbankEnum::PAY_TYPE_CHARGE) {
                $order = Order::alias('o')
                    ->join('order_course oc','oc.order_id = o.id')
                    ->where(['oc.course_id'=>$list['questionbank_id'],'o.user_id'=>$params['user_id'],'o.order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE,'o.refund_status'=>OrderEnum::REFUND_STATUS_NOT])
                    ->findOrEmpty();
                if ($order->isEmpty()) {
                    $result[$list['questionbank_id']]['btn_status'] = 4;
                }
            }
        }

        //分页
        $page_no = isset($params['page_no']) && $params['page_no'] != '' ? $params['page_no'] : 1;
        $page_size = isset($params['page_size']) && $params['page_size'] != '' ? $params['page_size'] : 25;
        $index = ($page_no - 1) * $page_size;
        $result = array_slice($result, $index, $page_size);

        //重置索引
        $result = array_values($result);

        return ['lists'=>$result,'count'=>count($result)];
    }


    /**
     * @notes 重新做题
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2024/1/4 11:22 上午
     */
    public function again($params)
    {
        Db::startTrans();
        try {
            $record = QuestionbankRecord::where(['questionbank_id'=>$params['id'],'user_id'=>$params['user_id'],'status'=>QuestionbankEnum::RECORD_STATUS_NOT])
                ->order(['id'=>'desc'])
                ->findOrEmpty();
            if (!$record->isEmpty()) {
                throw new \think\Exception('未交卷不能重新做题', 10006);
            }

            QuestionbankRecord::create([
                'questionbank_id' => $params['id'],
                'user_id' => $params['user_id'],
            ]);

            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 购买题库
     * @param array $params
     * @param array $userInfo
     * @return array|string
     * @author ljj
     * @date 2024/1/4 3:51 下午
     */
    public function buy(array $params,array $userInfo)
    {
        try{
            Db::startTrans();

            $questionbank = Questionbank::where(['id'=>$params['id']])->findOrEmpty();
            if($questionbank->isEmpty() || $questionbank->status != 2){
                throw new \Exception('题库已下架');
            }
            if(QuestionbankEnum::PAY_TYPE_FREE == $questionbank->pay_type){
                throw new \Exception('免费题库，不需要购买');
            }

            $cancelOrder = ConfigService::get('transaction', 'cancel_unpaid_orders',1);
            $cancelOrderTimes = ConfigService::get('transaction', 'cancel_unpaid_orders_times',30) * 60;
            $cancelTime = 0;
            $now = time();
            if($cancelOrder){
                $cancelTime = $cancelOrderTimes + $now;
            }
            //写入
            $order                  = new Order();
            $order->user_id         = $userInfo['user_id'];
            $order->sn              = generate_sn($order,'sn');
            $order->order_terminal = $userInfo['terminal'];
            $order->order_status    = OrderEnum::ORDER_STSTUS_WAITPAY;
            $order->pay_status      = OrderEnum::PAY_STATUS_WAIT;
            $order->total_amount    = $questionbank->pay_amount;
            $order->order_amount    = $questionbank->pay_amount;
            $order->cancel_time     = $cancelTime;
            $order->type            = OrderEnum::ORDER_TYPE_QUESTIONBANK;
            $order->save();

            $orderCourse[] = [
                'course_id'         => $questionbank->id,
                'course_snap'       => $questionbank->toArray(),
            ];
            $order->orderCourse()->saveAll($orderCourse);
            //订单日志
            (new OrderLogLogic())->record(OrderLogEnum::TYPE_USER, OrderLogEnum::USER_ADD_ORDER, $order->id, $userInfo['user_id']);


            Db::commit();
            return [
                'order_id'  => $order->id,
                'type'      => 'order',
            ];

        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();
        }

    }
}