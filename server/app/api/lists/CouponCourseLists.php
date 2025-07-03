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

namespace app\api\lists;


use app\common\enum\CourseEnum;
use app\common\model\course\Course;

class CouponCourseLists extends BaseApiDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author ljj
     * @date 2023/8/28 10:18 上午
     */
    public function where()
    {
        $where[] = ['cl.id','=',$this->params['coupon_list_id']];
        $where[] = ['c.status','=',1];
        if(isset($this->params['info']) && $this->params['info']){
            $where[] = ['c.name','like','%'.$this->params['info'].'%'];
        }

        return $where;
    }

    /**
     * @notes 列表
     * @return array
     * @author ljj
     * @date 2023/8/28 10:19 上午
     */
    public function lists(): array
    {
        $lists = Course::alias('c')
            ->join('coupon_course cc','c.id = cc.course_id')
            ->join('coupon_list cl','cc.coupon_id = cl.coupon_id')
            ->field('c.id,c.type,c.name,c.synopsis,c.cover,c.fee_type,c.sell_price,c.line_price,c.status,c.is_choice,(c.study_num+c.virtual_study_num) as study_num')
            ->append(['type_desc','catalogue_count'])
            ->where($this->where())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['c.sort'=>'desc','c.id'=>'desc'])
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 数量
     * @return int
     * @author ljj
     * @date 2023/8/28 10:19 上午
     */
    public function count(): int
    {
        return Course::alias('c')
            ->join('coupon_course cc','c.id = cc.course_id')
            ->join('coupon_list cl','cc.coupon_id = cl.coupon_id')
            ->where($this->where())
            ->count();
    }
}