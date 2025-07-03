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

namespace app\adminapi\logic\questionbank;


use app\common\logic\BaseLogic;
use app\common\model\questionbank\QuestionbankRecord;

class QuestionbankRecordLogic extends BaseLogic
{
    /**
     * @notes 答题详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/12/28 2:21 下午
     */
    public function detail($params)
    {
        $record = QuestionbankRecord::field('id,questionbank_id,user_id')
            ->with(['record_log','topic'])
            ->append(['questionbank_name','finish_num','topic_num'])
            ->where(['id'=>$params['id']])
            ->findOrEmpty()
            ->toArray();

        $result['questionbank_name'] = $record['questionbank_name'];
        $result['finish_num'] = $record['finish_num'];
        $result['topic_num'] = $record['topic_num'];
        $record_log = array_column($record['record_log'],null,'topic_id');
        foreach ($record['topic'] as $key=>$topic) {
            $log = $record_log[$topic['id']] ?? [];
            if (!empty($log)) {
                $result['topic_lists'][] = [
                    'index' => ($key + 1),
                    'stem' => $topic['stem'],
                    'option' => $topic['option'],
                    'correct_answer' => implode('、',$topic['answer']),
                    'user_answer' => implode('、',$log['user_answer']),
                    'is_true' => $log['is_true'],
                    'type_desc' => $topic['type_desc'],
                ];
            }
        }
        return $result;
    }
}