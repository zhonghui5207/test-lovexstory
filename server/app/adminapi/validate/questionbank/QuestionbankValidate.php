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

class QuestionbankValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'category_id' => 'require',
        'name' => 'require',
        'pay_type' => 'require|in:1,2|checkPayamount',
//        'pay_amount' => 'requireIf:pay_type,1|float|gt:0',
        'course_id' => 'array',
    ];

    protected $message = [
        'id.require' => '请选择题库',
        'category_id.require' => '请选择题库分类',
        'name.require' => '请输入题库名称',
        'pay_type.require' => '请选择付费方式',
        'pay_type.in' => '付费方式值错误',
        'pay_amount.requireIf' => '请输入购买价格',
        'pay_amount.float' => '购买价格值错误',
        'pay_amount.gt' => '购买价格必须大于零',
        'course_id.array' => '关联课程值错误',
    ];

    public function sceneAdd()
    {
        return $this->only(['category_id','name','pay_type','course_id']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','category_id','name','pay_type','course_id']);
    }

    public function scenePublish()
    {
        return $this->only(['id'])
            ->append('id','checkPublish');
    }

    public function sceneOff()
    {
        return $this->only(['id'])
            ->append('id','checkOff');
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }


    /**
     * @notes 校验发布
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     * @author ljj
     * @date 2023/12/27 10:39 上午
     */
    public function checkPublish($value,$rule,$data)
    {
        $count = QuestionbankTopic::where(['questionbank_id'=>$data['id']])->count();
        if ($count <= 0) {
            return '题库未添加题目，无法发布';
        }
        return true;
    }

    /**
     * @notes 校验下架
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/12/27 11:05 上午
     */
    public function checkOff($value,$rule,$data)
    {
        $result = Questionbank::where(['id'=>$data['id']])->findOrEmpty()->toArray();
        if ($result['status'] != QuestionbankEnum::STATUS_ING) {
            return '题库未发布，不能下架';
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
     * @date 2023/12/27 11:12 上午
     */
    public function checkDel($value,$rule,$data)
    {
        $result = Questionbank::where(['id'=>$data['id']])->findOrEmpty()->toArray();
        if ($result['status'] == QuestionbankEnum::STATUS_ING) {
            return '进行中的题库，不能删除';
        }
        return true;
    }


    /**
     * @notes 校验购买价格
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/12/28 10:27 上午
     */
    public function checkPayamount($value,$rule,$data)
    {
        if ($data['pay_type'] == QuestionbankEnum::PAY_TYPE_CHARGE) {
            if (isset($data['pay_amount']) && $data['pay_amount'] == 0) {
                return '价格不能为0';
            }
            if (!isset($data['pay_amount']) || !is_numeric($data['pay_amount']) || $data['pay_amount'] <= 0) {
                return '购买价格值错误';
            }
        }
        return true;
    }
}