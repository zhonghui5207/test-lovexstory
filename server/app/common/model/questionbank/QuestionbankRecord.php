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
use think\model\concern\SoftDelete;

class QuestionbankRecord extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    /**
     * @notes 关联答题记录日志
     * @return \think\model\relation\HasMany
     * @author ljj
     * @date 2023/12/28 11:10 上午
     */
    public function recordLog()
    {
        return $this->hasMany(QuestionbankRecordLog::class,'record_id','id')
            ->field('id,record_id,topic_id,user_answer,is_true')
            ->json(['user_answer'],true);
    }

    /**
     * @notes 关联题目
     * @return \think\model\relation\HasMany
     * @author ljj
     * @date 2023/12/28 11:12 上午
     */
    public function topic()
    {
        return $this->hasMany(QuestionbankTopic::class,'questionbank_id','questionbank_id')
            ->field('id,questionbank_id,type,stem,illustration,option,answer,analysis,difficulty,points')
            ->append(['type_desc','difficulty_desc'])
            ->json(['answer','option'],true);
    }

    /**
     * @notes 已完成题目数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/12/27 6:33 下午
     */
    public function getFinishNumAttr($value,$data)
    {
        return QuestionbankRecordLog::where(['record_id'=>$data['id'],'user_id'=>$data['user_id']])->count();
    }

    /**
     * @notes 总题目数量
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/12/27 6:33 下午
     */
    public function getTopicNumAttr($value,$data)
    {
        return QuestionbankTopic::where(['questionbank_id'=>$data['questionbank_id']])->count();
    }

    /**
     * @notes 答题状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2023/12/27 6:45 下午
     */
    public function getStatusDescAttr($value,$data)
    {
        return QuestionbankEnum::getRecordStatusDesc($data['status']);
    }

    /**
     * @notes 最后记录时间
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/12/27 6:47 下午
     */
    public function getLastTimeAttr($value,$data)
    {
        $result = QuestionbankRecordLog::where(['record_id'=>$data['id'],'user_id'=>$data['user_id']])->order(['update_time'=>'desc'])->value('update_time');
        return date('Y-m-d H:i:s',$result);
    }

    /**
     * @notes 题库名称
     * @param $value
     * @param $data
     * @return mixed
     * @author ljj
     * @date 2023/12/28 11:13 上午
     */
    public function getQuestionbankNameAttr($value,$data)
    {
        return Questionbank::where(['id'=>$data['questionbank_id']])->value('name');
    }
}