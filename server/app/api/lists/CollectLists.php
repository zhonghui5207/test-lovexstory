<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------
namespace app\api\lists;
use app\common\lists\BaseDataLists;
use app\common\model\course\Course;
use app\common\model\course\CourseCatalogue;

/**
 * 课程收藏列表类
 * Class CollectLists
 * @package app\api\lists
 */
class CollectLists extends BaseApiDataLists
{

    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {
        $lists = Course::alias('C')
                ->where(['user_id'=>$this->userId])
                ->join('course_collect CC','C.id = CC.course_id')
                ->field('C.id,name,fee_type,sell_price,cover,type,(virtual_study_num+study_num) as study_num,sell_price')
                ->limit($this->limitOffset, $this->limitLength)
                ->select();
        $cataglogue = CourseCatalogue::group('course_id')->column('count(id) as num','course_id');
        foreach ($lists as $list)
        {
            $list['categlogue_num'] = $cataglogue[$list->id] ?? 0;
        }

        return $lists->toArray();
    }

    /**
     * @notes 实现数据列表记录数
     * @return int
     * @author 令狐冲
     * @date 2021/7/6 00:34
     */
    public function count(): int
    {

        $count = Course::alias('C')
            ->where(['user_id'=>$this->userId])
            ->join('course_collect CC','C.id = CC.course_id')
            ->count();
        return $count;

    }
}