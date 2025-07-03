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


use app\common\enum\QuestionbankEnum;
use app\common\model\BaseModel;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

class QuestionbankTopic extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 题库分类名称
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/12/27 12:03 下午
     */
    public function getCategoryNameAttr($value,$data)
    {
        $category_id = Questionbank::where(['id'=>$data['questionbank_id']])->value('category_id');
        return QuestionbankCategory::where(['id'=>$category_id])->value('name');
    }

    /**
     * @notes 题库名称
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/12/27 2:58 下午
     */
    public function getQuestionbankNameAttr($value,$data)
    {
        return Questionbank::where(['id'=>$data['questionbank_id']])->value('name');
    }

    /**
     * @notes 题目类型
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/12/27 12:15 下午
     */
    public function getTypeDescAttr($value,$data)
    {
        return QuestionbankEnum::getTopicTypeDesc($data['type']);
    }

    /**
     * @notes 题目难度
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/12/27 12:15 下午
     */
    public function getDifficultyDescAttr($value,$data)
    {
        return QuestionbankEnum::getTopicDifficultyDesc($data['difficulty']);
    }

    /**
     * @notes 获取题干插图
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/12/27 3:00 下午
     */
    public function getIllustrationAttr($value,$data)
    {
        return empty($value) ? '' : FileService::getFileUrl($value);
    }

    /**
     * @notes 设置题干插图
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2023/12/27 3:00 下午
     */
    public function setIllustrationAttr($value,$data)
    {
        return empty($value) ? '' : FileService::setFileUrl($value);
    }
}