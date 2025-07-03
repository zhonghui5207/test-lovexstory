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
use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseCatalogue;
use app\common\model\File;
use app\common\service\FileService;

/**
 * 课程目录逻辑层
 * Class CourseCatalogueLogic
 * @package app\adminapi\logic\course
 */
class CourseCatalogueLogic
{

    /**
     * @notes 添加目录
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/23 11:55
     */
    public function add(array $params):bool
    {
        $course = Course::where(['id'=>$params['course_id']])
            ->field('type,fee_type')
            ->findOrEmpty();
        0 == $course->fee_type && $params['fee_type'] = 0;
        $courseCatalogue = new CourseCatalogue();
        $courseCatalogue->name          = $params['name'];
        $courseCatalogue->cover         = !empty($params['cover']) ? FileService::setFileUrl($params['cover']) : '';
        $courseCatalogue->fee_type      = $params['fee_type'] ?? 0;
        $courseCatalogue->course_id     = $params['course_id'];
        $courseCatalogue->content       = $params['content'];
        if(CourseEnum::COURSE_TYPE_IMGAE_TXT !=$course->type){
            $courseCatalogue->content   = FileService::setFileUrl($params['content']);
            //获取音视频时长
            $courseCatalogue->duration   = round(getFileDuration($courseCatalogue->content));
        }
        $courseCatalogue->sort          = $params['sort'];
        $courseCatalogue->status        = $params['status'];
        $courseCatalogue->save();
        return true;

    }

    /**
     * @notes 获取目录详情
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/5/23 15:54
     */
    public function detail(int $id):array
    {
        $courseCatelogue = CourseCatalogue::withoutField('account,create_time,update_time,delete_time')
            ->findOrEmpty($id);
        $content = $courseCatelogue->getData('content');
        $courseCatelogue->content_name = '';
        if($content){
            $courseCatelogue->content_name = File::where(['uri'=>$content])->value('name');
        }
        $courseCatelogue = $courseCatelogue->toArray();
        $courseType = Course::where(['id'=>$courseCatelogue['course_id']])->value('type');
        if(CourseEnum::COURSE_TYPE_VOICE == $courseType || CourseEnum::COURSE_TYPE_VIDEO == $courseType){
            $courseCatelogue['content'] = FileService::getFileUrl($courseCatelogue['content']);
        }
        return $courseCatelogue;
    }

    /**
     * @notes 编辑目录
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/23 12:01
     */
    public function edit(array $params):bool
    {
        $courseCatelogue = CourseCatalogue::findOrEmpty($params['id']);
        $courseType = Course::where(['id' => $courseCatelogue->course_id])->value('type');
        $data = [
            'id'        => $params['id'],
            'name'      => $params['name'],
            'cover'     => !empty($params['cover']) ? FileService::setFileUrl($params['cover']) : '',
            'fee_type'  => $params['fee_type'],
            'content'   => $params['content'],
            'sort'      => $params['sort'],
            'status'    => $params['status'],
        ];
        if(CourseEnum::COURSE_TYPE_IMGAE_TXT != $courseType){
            $data['content'] = FileService::setFileUrl($params['content']);
            //获取音视频时长
            $data['duration'] = round(getFileDuration($data['content']));
        }
        CourseCatalogue::update($data);
        return true;
    }

    /**
     * @notes 删除目录
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2022/5/23 12:03
     */
    public function del(int $id):bool
    {
        CourseCatalogue::destroy($id);
        return true;
    }

    /**
     * @notes 修改收费类型
     * @param array $params
     * @author cjhao
     * @date 2022/7/1 15:16
     */
    public function changeFeeType(array $params)
    {
        $courseCatelogue = CourseCatalogue::findOrEmpty(['id'=>$params['id']]);
        if($courseCatelogue->isEmpty()){
            return '目录不存在';
        }
        $course = Course::findOrEmpty($courseCatelogue->course_id);
        if($course->isEmpty()){
            return '目录数据错误';
        }
        if(CourseEnum::FEE_TYPE_FREE == $course->fee_type && CourseEnum::FEE_TYPE_FEE == $params['fee_type']){
            return '当前课程是免费的，目录不允许设置成收费';
        }
        CourseCatalogue::where(['id'=>$params['id']])->update(['fee_type'=>$params['fee_type']]);
        return true;
    }


    /**
     * @notes 设置配置接口
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/11/18 11:57
     */
    public function changeStatus(array $params)
    {
        CourseCatalogue::where(['id'=>$params['id']])->update(['status'=>$params['status']]);
        return true;
    }
}