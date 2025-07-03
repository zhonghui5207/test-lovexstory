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
use app\common\model\course\Course;

/**
 * 课程列表逻辑类
 * Class CourseLists
 * @package app\api\lists
 */
class CourseLists extends BaseApiDataLists
{


    /**
     * @notes 实现数据列表
     * @return array
     * @author 令狐冲
     * @date 2021/7/6 00:33
     */
    public function lists(): array
    {

        $lists = Course::where(['status'=>1])
                ->where($this->setSearch())
                ->field('id,name,cover,type,fee_type,sell_price,(study_num+virtual_study_num) as study_num')
                ->withCount(['catalogue'])
                ->limit($this->limitOffset, $this->limitLength)
                ->select();

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

        $count = Course::where(['status'=>1])
            ->where($this->setSearch())
            ->field('id,name,cover,type,fee_type,sell_price')
            ->count();

        return $count;
    }

    public function setSearch():array
    {
        $where = [];
        if(isset($this->params['name']) && $this->params['name']){
            $where[] = ['name','like','%'.$this->params['name'].'%'];
        }
        if(isset($this->params['is_choice']) && true == $this->params['is_choice']){
            $where[] = ['is_choice','=',1];
        }
        if(isset($this->params['category_id']) && $this->params['category_id']){
            $where[] = ['category_id','=',$this->params['category_id']];
        }
        return $where;
    }

//    /**
//     * @notes 设置搜索条件
//     * @return array
//     * @author 令狐冲
//     * @date 2021/7/7 19:44
//     */
//    public function setSearch(): array
//    {
//        return [
//            '%like%'    => ['name'],
//            '='         => ['is_choice','category_id'],
//        ];
//    }
}