<?php
namespace app\api\lists;
use app\common\model\course\Course;

/**
 * 热门课程列表
 * Class HostLists
 * @package app\api\lists
 */
class HostLists extends BaseApiDataLists
{

    /**
     * @notes 课程列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/11/29 5:35 下午
     */
    public function lists(): array
    {
        $lists = Course::where(['status'=>1])
            ->field('id,name,type,cover,fee_type,sell_price,(virtual_study_num+study_num) as study_num,create_time')
            ->order('study_num desc,sort desc,id desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->withCount(['catalogue'])
            ->select();
        return $lists->toArray();

    }

    /**
     * @notes 课程数量
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/11/29 5:35 下午
     */
    public function count(): int
    {
        return Course::where(['status'=>1])->count();
    }
}