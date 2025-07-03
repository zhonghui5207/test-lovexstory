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
use app\common\model\questionbank\QuestionbankTopic;

class QuestionbankTopicLogic extends BaseLogic
{
    /**
     * @notes 添加题目
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 2:41 下午
     */
    public function add($params)
    {
        QuestionbankTopic::create([
            'questionbank_id' => $params['questionbank_id'],
            'type' => $params['type'],
            'stem' => $params['stem'],
            'illustration' => $params['illustration'] ?? '',
            'option' => json_encode($params['option']),
            'answer' => json_encode($params['answer']),
            'analysis' => $params['analysis'],
            'difficulty' => $params['difficulty'] ?? null,
            'points' => $params['points'] ?? '',
        ]);
        return true;
    }

    /**
     * @notes 题目详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/12/27 3:01 下午
     */
    public function detail($params)
    {
        $result = QuestionbankTopic::where(['id'=>$params['id']])->json(['option','answer'],true)
            ->append(['questionbank_name'])
            ->findOrEmpty()
            ->toArray();
        return $result;
    }

    /**
     * @notes 编辑题目
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 3:09 下午
     */
    public function edit($params)
    {
        QuestionbankTopic::update([
            'type' => $params['type'],
            'stem' => $params['stem'],
            'illustration' => $params['illustration'] ?? '',
            'option' => json_encode($params['option']),
            'answer' => json_encode($params['answer']),
            'analysis' => $params['analysis'],
            'difficulty' => $params['difficulty'] ?? null,
            'points' => $params['points'] ?? '',
        ],['id'=>$params['id']]);
        return true;
    }

    /**
     * @notes 删除题目
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 3:18 下午
     */
    public function del($params)
    {
        QuestionbankTopic::destroy($params['id']);
        return true;
    }
}