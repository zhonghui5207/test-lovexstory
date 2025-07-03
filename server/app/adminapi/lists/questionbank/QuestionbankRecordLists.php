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

namespace app\adminapi\lists\questionbank;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\questionbank\QuestionbankRecord;

class QuestionbankRecordLists extends BaseAdminDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/12/27 6:04 下午
     */
    public function where(): array
    {
        $where = [];
        if(isset($this->params['user_info']) && $this->params['user_info']){
            $where[] = ['u.nickname|u.mobile|u.account','like','%'.$this->params['user_info'].'%'];
        }
        if(isset($this->params['questionbank_name']) && $this->params['questionbank_name']){
            $where[] = ['q.name','like','%'.$this->params['questionbank_name'].'%'];
        }
        if(isset($this->params['category_id']) && $this->params['category_id']){
            $where[] = ['q.category_id','=',$this->params['category_id']];
        }
        return $where;
    }

    /**
     * @notes 答题记录列表
     * @return array
     * @author ljj
     * @date 2023/12/27 6:17 下午
     */
    public function lists(): array
    {
        $list = QuestionbankRecord::alias('qr')
            ->join('questionbank q', 'q.id = qr.questionbank_id')
            ->join('user u', 'u.id = qr.user_id')
            ->field('qr.id,u.nickname,q.name as questionbank_name,qr.status,qr.user_id,q.id as questionbank_id')
            ->append(['finish_num','topic_num','status_desc','last_time'])
            ->whereNull('q.delete_time')
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['qr.id'=>'desc'])
            ->select();

        return $list->toArray();

    }

    /**
     * @notes 答题记录数量
     * @return int
     * @author ljj
     * @date 2023/12/27 6:17 下午
     */
    public function count(): int
    {
        return QuestionbankRecord::alias('qr')
            ->join('questionbank q', 'q.id = qr.questionbank_id')
            ->join('user u', 'u.id = qr.user_id')
            ->whereNull('q.delete_time')
            ->where($this->where())
            ->count();
    }
}