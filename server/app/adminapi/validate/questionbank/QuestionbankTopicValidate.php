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

namespace app\adminapi\validate\questionbank;


use app\common\enum\QuestionbankEnum;
use app\common\model\questionbank\Questionbank;
use app\common\model\questionbank\QuestionbankTopic;
use app\common\validate\BaseValidate;

class QuestionbankTopicValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'questionbank_id' => 'require|checkQuestionbank',
        'type' => 'require|in:1,2,3',
        'stem' => 'require',
        'option' => 'require|array',
        'answer' => 'require|array',
        'analysis' => 'require',
        'difficulty' => 'in:1,2,3',
    ];

    protected $message = [
        'id.require' => '请选择题目',
        'questionbank_id.require' => '请选择题库',
        'type.require' => '请选择题型',
        'type.in' => '题型值错误',
        'stem.require' => '请输入题干',
        'option.require' => '请输入选项',
        'option.array' => '选项值错误',
        'answer.require' => '请输入答案',
        'answer.array' => '答案值错误',
        'analysis.require' => '请输入题目解析',
        'difficulty.in' => '题目难度值错误',
    ];

    public function sceneAdd()
    {
        return $this->only(['questionbank_id','type','stem','option','answer','analysis','difficulty']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','questionbank_id','type','stem','option','answer','analysis','difficulty']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }


    /**
     * @notes 校验题库
     * @param $value
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/12/27 2:35 下午
     */
    public function checkQuestionbank($value,$rule,$data)
    {
        $questionbank = Questionbank::where(['id'=>$data['questionbank_id']])->findOrEmpty()->toArray();
        if ($questionbank['status'] != QuestionbankEnum::STATUS_NOT) {
            return '未发布的题库才可以进行新增、编辑、删除操作';
        }
        return true;
    }

    /**
     * @notes 校验删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/12/27 3:18 下午
     */
    public function checkDel($value,$rule,$data)
    {
        $result = QuestionbankTopic::where(['id'=>$data['id']])->findOrEmpty()->toArray();
        $questionbank = Questionbank::where(['id'=>$result['questionbank_id']])->findOrEmpty()->toArray();
        if ($questionbank['status'] != QuestionbankEnum::STATUS_NOT) {
            return '未发布的题库才可以进行新增、编辑、删除操作';
        }
        return true;
    }
}