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
namespace app\api\logic;

use app\common\model\course\CourseCollect;

/**
 * 收藏逻辑层
 * Class CollectLogic
 * @package app\api\logic
 */
class CollectLogic
{
    /**
     * @notes 课程收藏接口
     * @param array $params
     * @author cjhao
     * @date 2022/6/7 15:46
     */
    public function collect(array $params):bool
    {

        if(0 == $params['collect']){
            CourseCollect::where(['user_id'=>$params['user_id'],'course_id'=>$params['id']])
                ->delete();

        }else{
            $collect = CourseCollect::where(['user_id'=>$params['user_id'],'course_id'=>$params['id']])
                ->findOrEmpty();
            if($collect->isEmpty()){
                CourseCollect::create(['user_id'=>$params['user_id'],'course_id'=>$params['id']]);
            }

        }
        return true;

    }

}