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

declare(strict_types=1);
namespace app\common\service;
use app\common\enum\DecorateEnum;
use app\common\model\decorate\Subject;
use app\common\model\decorate\SubjectCourse;

/**
 * 装修服务类
 * Class DecoratePageService
 * @package app\common\service
 */
class DecoratePageService
{

    public static function getModuleData(array $content,array $config = []):array
    {
        $source         = $config['source'] ?? '';          //后台组件替换或商城组件替换

        $moduleList = array_column($content,'name');
        foreach ($moduleList as $moduleKey => $moduleName)
        {
            switch ($moduleName){

                case DecorateEnum::SPECIAL:
                    $moduleContent = $content[$moduleKey]['content'];

                    if(0 == $moduleContent['enabled']){
                        $content[$moduleKey]['content']['data'] = [];
                    }
                    $specialData = $moduleContent['data'];
                    $sort = [];
                    $ids = [];
                    if(empty($specialData)){
                        break;
                    }
                    foreach ($specialData as $key => $data){
                        $sort[] = $data['sort'];
                        if(0 == $data['status'] && 'admin' != $source){
                            continue;
                        }
                        $ids[] = $data['id'];
                    }
                    array_multisort($sort,  SORT_DESC, SORT_NUMERIC, $specialData);
                    $orderFieldIds = array_column($specialData,'id');
                    $orderFieldIds = implode(',',$orderFieldIds);
                    $orderField = "field(id,$orderFieldIds)";
                    //指定字段排序
                    $lists = subject::where(['id'=>$ids])
                        ->orderRaw($orderField)
                        ->column('name,cover,create_time','id');

                    $subjectCourseLists = SubjectCourse::where(['subject_id'=>$ids])
                        ->field('subject_id,course_id')
                        ->with('courseLists')
                        ->select()->toArray();
                    foreach ($subjectCourseLists as $subjectCourse)
                    {
                        if(isset($lists[$subjectCourse['subject_id']])){
                            $courseNum = $lists[$subjectCourse['subject_id']]['course_num'] ?? 0;
                            $subjectCourseNum = $subjectCourse['virtual_study_num']+$subjectCourse['study_num'];
                            $lists[$subjectCourse['subject_id']]['course_num'] = $courseNum + $subjectCourseNum;

                        }
                    }
                    $specialData = array_column($specialData,null,'id');

                    foreach ($lists as $key => $subject){
                        $lists[$key]['cover'] = FileService::getFileUrl($subject['cover']);
                        if(!isset($subject['course_num'])){
                            $lists[$key]['course_num'] = 0;
                        }
                        $lists[$key]['sort'] = $specialData[$key]['sort'];
                        $lists[$key]['status'] = $specialData[$key]['status'];
                    }

                    $content[$moduleKey]['content']['data'] = array_values($lists);
                    break;
            }
        }
        return $content;


    }

}