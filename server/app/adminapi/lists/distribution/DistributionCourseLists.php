<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\adminapi\lists\distribution;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsExcelInterface;
use app\common\model\course\Course;


class DistributionCourseLists extends BaseAdminDataLists implements ListsExcelInterface
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/10/24 5:45 下午
     */
    public function where(): array
    {
        $where = [];
        if (isset($this->params['course_info']) && !empty($this->params['course_info'])) {
            $where[] = ['name', 'like', '%'. $this->params['course_info'] . '%'];
        }
        if (isset($this->params['category_id']) && !empty($this->params['category_id'])) {
            $where[] = ['category_id', '=',$this->params['category_id']];
        }
        if (isset($this->params['distribution_status']) && $this->params['distribution_status'] != '') {
            $where[] = ['distribution_status', '=',$this->params['distribution_status']];
        }

        return $where;
    }

    /**
     * @notes 分销课程列表
     * @return array
     * @author ljj
     * @date 2023/10/24 5:53 下午
     */
    public function lists(): array
    {
        $lists = Course::field('id,cover,name,sell_price,status as sale_status,distribution_status,distribution_rule')
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order('id', 'desc')
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['distribution_status_desc'] = $item['distribution_status'] ? '参与' : '不参与';
            $item['sale_status_desc'] = $item['sale_status'] == 1 ? '销售中' : '已下架';
            $item['distribution_rule_desc'] = $item['distribution_rule'] == 2 ? '自定义设置' : '默认分销等级佣金规则';
        }

        return $lists;
    }

    /**
     * @notes 分销课程数量
     * @return int
     * @author ljj
     * @date 2023/10/24 5:54 下午
     */
    public function count(): int
    {
        return Course::where($this->where())->count();
    }

    /**
     * @notes 导出字段
     * @return string[]
     * @author ljj
     * @date 2023/10/24 5:54 下午
     */
    public function setExcelFields(): array
    {
        return [
            'name' => '课程名称',
            'sell_price' => '价格',
            'sale_status_desc' => '销售状态',
            'distribution_status_desc' => '分销状态',
            'rule_desc' => '分销规则',
        ];
    }

    /**
     * @notes 导出表名
     * @return string
     * @author ljj
     * @date 2023/10/24 5:54 下午
     */
    public function setFileName(): string
    {
        return '分销课程表';
    }
}