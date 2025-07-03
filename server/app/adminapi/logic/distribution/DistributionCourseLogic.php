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

namespace app\adminapi\logic\distribution;

use app\common\enum\CourseEnum;
use app\common\logic\BaseLogic;
use app\common\model\course\Course;
use app\common\model\distribution\DistributionCourse;
use app\common\model\distribution\DistributionLevel;
use think\facade\Db;


class DistributionCourseLogic extends BaseLogic
{
    /**
     * @notes 分销课程详情
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/25 10:19 上午
     */
    public static function detail($params)
    {
        $course = Course::field('id,name,cover,distribution_status,distribution_rule,sell_price')->where('id', $params['id'])->findOrEmpty()->toArray();
        $distribution_level = DistributionLevel::field('id,name,self_ratio,first_ratio,second_ratio')->select()->toArray();
        $course['rule'] = [];
        //默认分销等级佣金规则
        if ($course['distribution_rule'] == CourseEnum::DISTRIBUTION_RULE_DEFAULT) {
            foreach ($distribution_level as $level) {
                $course['rule'][] = [
                    'level_name' => $level['name'],
                    'level_id' => $level['id'],
                    'sell_price' => $course['sell_price'],
                    'self_ratio' => $level['self_ratio'],
                    'first_ratio' => $level['first_ratio'],
                    'second_ratio' => $level['second_ratio'],
                ];
            }
        }
        //自定义设置
        if ($course['distribution_rule'] == CourseEnum::DISTRIBUTION_RULE_CUSTOM) {
            $distribution_course = DistributionCourse::where(['course_id'=>$course['id']])->column('self_ratio,first_ratio,second_ratio','level_id');
            foreach ($distribution_level as $level) {
                $course['rule'][] = [
                    'level_name' => $level['name'],
                    'level_id' => $level['id'],
                    'sell_price' => $course['sell_price'],
                    'self_ratio' => $distribution_course[$level['id']]['self_ratio'] ?? 0,
                    'first_ratio' => $distribution_course[$level['id']]['first_ratio'] ?? 0,
                    'second_ratio' => $distribution_course[$level['id']]['second_ratio'] ?? 0,
                ];
            }
        }

        return $course;
    }

    /**
     * @notes 设置佣金
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2023/10/25 10:43 上午
     */
    public static function set($params)
    {
        Db::startTrans();
        try {
            // 删除旧数据
            DistributionCourse::where(['course_id'=>$params['id']])->delete();

            // 生成新数据
            if ($params['distribution_rule'] == CourseEnum::DISTRIBUTION_RULE_CUSTOM) {
                $data= [];
                foreach($params['rule'] as $item) {
                    $data[] = [
                        'course_id' => $params['id'],
                        'level_id' => $item['level_id'],
                        'self_ratio' => $item['self_ratio'],
                        'first_ratio' => $item['first_ratio'],
                        'second_ratio' => $item['second_ratio'],
                    ];
                }
                (new DistributionCourse())->saveAll($data);
            }

            //更新课程分销状态
            Course::update(['distribution_status'=>$params['distribution_status'],'distribution_rule'=>$params['distribution_rule']],['id'=>$params['id']]);

            Db::commit();
            return true;
        }catch(\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 参与/取消分销
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/10/25 10:52 上午
     */
    public static function join($params)
    {
        foreach ($params['ids'] as $id) {
            $course = Course::find($id);
            $course->distribution_status = $params['distribution_status'];
            $course->save();
        }
        return true;
    }
}