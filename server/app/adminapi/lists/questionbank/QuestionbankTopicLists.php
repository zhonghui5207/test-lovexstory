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
use app\common\lists\ListsExtendInterface;
use app\common\model\questionbank\Questionbank;
use app\common\model\questionbank\QuestionbankTopic;

class QuestionbankTopicLists extends BaseAdminDataLists implements ListsExtendInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/12/27 11:55 上午
     */
    public function where(): array
    {
        $where = [];
        $where[] = ['questionbank_id','=',$this->params['questionbank_id']];
        if(isset($this->params['stem']) && $this->params['stem']){
            $where[] = ['stem','like','%'.$this->params['stem'].'%'];
        }
        if(isset($this->params['type']) && $this->params['type']){
            $where[] = ['type','=',$this->params['type']];
        }
        return $where;
    }

    /**
     * @notes 题目列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/27 11:59 上午
     */
    public function lists(): array
    {
        $list = QuestionbankTopic::field('id,questionbank_id,type,stem,difficulty,create_time')
            ->append(['category_name','type_desc','difficulty_desc'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id'=>'asc'])
            ->select();

        return $list->toArray();

    }

    /**
     * @notes 题目数量
     * @return int
     * @author ljj
     * @date 2023/12/27 11:59 上午
     */
    public function count(): int
    {
        return QuestionbankTopic::where($this->where())->count();
    }

    /**
     * @notes 扩展数据
     * @return array
     * @author ljj
     * @date 2023/12/27 11:58 上午
     */
    public function extend()
    {
        $questionbank = Questionbank::where(['id'=>$this->params['questionbank_id']])->findOrEmpty()->toArray();
        return [
            'questionbank_name' => $questionbank['name'],
            'questionbank_id' => $questionbank['id'],
            'questionbank_status' => $questionbank['status'],
        ];
    }
}