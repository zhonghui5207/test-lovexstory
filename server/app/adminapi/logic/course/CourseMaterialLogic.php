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
namespace app\adminapi\logic\course;
use app\common\model\course\CourseMaterial;
use app\common\model\File;

class CourseMaterialLogic
{

    /**
     * @notes 获取课程资料
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2022/5/23 16:50
     */
    public function detail(int $id):array
    {
        $courseMaterial = CourseMaterial::withoutField('create_time,update_time,delete_time')
            ->where(['course_id'=>$id])
            ->findOrEmpty();
        if(!$courseMaterial->isEmpty()){
            $content = $courseMaterial->getData('content');
            $courseMaterial->content_name = '';
            if($content){
                $courseMaterial->content_name = File::where(['uri'=>$content])->value('name');
            }
        }
        return $courseMaterial->toArray();

    }

    /**
     * @notes 设置课程资料
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/23 17:45
     */
    public function set(array $params):bool
    {
        $courseMaterial = CourseMaterial::where(['course_id' => $params['course_id']])->findOrEmpty();
        $courseMaterial->code = $params['code'];
        $courseMaterial->content = $params['content'];
        $courseMaterial->course_id = $params['course_id'];
        $courseMaterial->is_link = $params['is_link'];
        $courseMaterial->link = $params['link'];
        $courseMaterial->save();
        return true;
    }

}