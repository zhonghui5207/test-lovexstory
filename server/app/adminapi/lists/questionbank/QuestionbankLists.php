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
use app\common\model\questionbank\Questionbank;

class QuestionbankLists extends BaseAdminDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/12/26 6:35 下午
     */
    public function where(): array
    {
        $where = [];
        if(isset($this->params['name']) && $this->params['name']){
            $where[] = ['name','like','%'.$this->params['name'].'%'];
        }
        if(isset($this->params['category_id']) && $this->params['category_id']){
            $where[] = ['category_id','=',$this->params['category_id']];
        }
        if(isset($this->params['status']) && $this->params['status']){
            $where[] = ['status','=',$this->params['status']];
        }
        return $where;
    }

    /**
     * @notes 题库列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/26 6:38 下午
     */
    public function lists(): array
    {
        $list = Questionbank::field('id,category_id,name,pay_amount,status,course_id,create_time')
            ->append(['category_name','topic_num','course_num','status_desc'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id'=>'asc'])
            ->select();

        return $list->toArray();

    }

    /**
     * @notes 题库数量
     * @return int
     * @author ljj
     * @date 2023/12/26 6:37 下午
     */
    public function count(): int
    {
        return Questionbank::where($this->where())->count();
    }
}