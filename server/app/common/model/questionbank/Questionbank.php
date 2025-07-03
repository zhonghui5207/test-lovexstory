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

namespace app\common\model\questionbank;


use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\enum\QuestionbankEnum;
use app\common\model\BaseModel;
use app\common\model\order\Order;
use think\model\concern\SoftDelete;

class Questionbank extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 题库分类名称
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/12/26 6:39 下午
     */
    public function getCategoryNameAttr($value,$data)
    {
        return QuestionbankCategory::where(['id'=>$data['category_id']])->value('name');
    }

    /**
     * @notes 题目数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/12/26 6:40 下午
     */
    public function getTopicNumAttr($value,$data)
    {
        return QuestionbankTopic::where(['questionbank_id'=>$data['id']])->count();
    }

    /**
     * @notes 关联课程数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/12/26 6:42 下午
     */
    public function getCourseNumAttr($value,$data)
    {
        $result = 0;
        if (!empty($data['course_id'])) {
            $course = json_decode($data['course_id'],true);
            $result = count($course);
        }
        return $result;
    }

    /**
     * @notes 题库状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/12/26 6:49 下午
     */
    public function getStatusDescAttr($value,$data)
    {
        return QuestionbankEnum::getStatusDesc($data['status']);
    }
}