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


use app\common\enum\QuestionbankEnum;
use app\common\logic\BaseLogic;
use app\common\model\course\Course;
use app\common\model\questionbank\Questionbank;

class QuestionbankLogic extends BaseLogic
{
    /**
     * @notes 添加题库
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 9:53 上午
     */
    public function add($params)
    {
        Questionbank::create([
            'category_id' => $params['category_id'],
            'name' => $params['name'],
            'pay_type' => $params['pay_type'],
            'pay_amount' => $params['pay_type'] == QuestionbankEnum::PAY_TYPE_CHARGE ? $params['pay_amount'] : 0,
            'course_id' => json_encode($params['course_id'] ?? []),
        ]);
        return true;
    }

    /**
     * @notes 题库详情
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/27 10:24 上午
     */
    public function detail($params)
    {
        $result = Questionbank::where(['id'=>$params['id']])->json(['course_id'],true)->append(['category_name'])->findOrEmpty()->toArray();
        $result['course_lists'] = Course::where(['id'=>$result['course_id']])
            ->field('id,type,name,cover,sell_price')
            ->append(['type_desc'])
            ->select()
            ->toArray();
        return $result;
    }

    /**
     * @notes 编辑题库
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 10:31 上午
     */
    public function edit($params)
    {
        $questionbank = Questionbank::find($params['id']);
        $questionbank->category_id = $params['category_id'];
        $questionbank->name = $params['name'];
        $questionbank->pay_amount = $params['pay_type'] == QuestionbankEnum::PAY_TYPE_CHARGE ? $params['pay_amount'] : 0;
        $questionbank->course_id = $params['course_id'];
        if ($questionbank->status == QuestionbankEnum::STATUS_NOT) {
            $questionbank->pay_type = $params['pay_type'];
        }
        $questionbank->save();
        return true;
    }

    /**
     * @notes 发布
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 10:40 上午
     */
    public function publish($params)
    {
        Questionbank::update([
            'status' => QuestionbankEnum::STATUS_ING,
        ],['id'=>$params['id']]);
        return true;
    }

    /**
     * @notes 下架
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 11:10 上午
     */
    public function off($params)
    {
        Questionbank::update([
            'status' => QuestionbankEnum::STATUS_END,
        ],['id'=>$params['id']]);
        return true;
    }

    /**
     * @notes 删除题库
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/27 11:12 上午
     */
    public function del($params)
    {
        Questionbank::destroy($params['id']);
        return true;
    }
}