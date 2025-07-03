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
namespace app\common\logic;
use app\common\enum\DecorateEnum;
use app\common\model\course\Course;
use app\common\model\decorate\Subject;

/**
 * 装修公共逻辑类
 * Class DecoratePageLogic
 * @package app\common\logic
 */
class DecoratePageLogic
{

    /**
     * @notes 替换组件数据
     * @param array $data
     * @param array $config
     * @return array
     * @author cjhao
     * @date 2022/6/30 17:10
     */
    public static function getModuleData(array $data,array $config = []):array
    {
        $content = $data['content'] ?? [];
        if(empty($content)){
            return $data;
        }
        $children = $content['children'] ?? [];
        if(empty($content)){
            return $data;
        }
        foreach ($children as $moduleName => $moduleData) {
            //专题组件
            if(DecorateEnum::SUBJECT === $moduleName){

                $contentData = $moduleData['content']['data'];
                if(empty($contentData)){
                    continue;
                }
                $sort = array_column($contentData,'sort');
                $sortIds = array_column($contentData,'sort','id');
                array_multisort($sort,SORT_DESC,$contentData);//对多个数组或多维数组进行排序
                $ids = array_column($contentData,'id');
                $orderField = implode(',', $ids);
                $subjectList = Subject::where(['id'=>$ids,'status'=>1])
                    ->withoutField('create_time,update_time,delete_time')
                    ->with(['course'])
                    ->orderRaw("field(id,$orderField)")
                    ->select();
                $courseLists = Course::column('(virtual_study_num+study_num) as study_num','id');


                foreach ($subjectList as $subject){
                    $subject->sort = $sortIds[$subject->id] ?? 0;
                    $courseIds = array_column($subject->course->toarray(),'course_id');
                    $studyNum = 0;
                    foreach ($courseIds as $courseId){
                        $studyNum+= $courseLists[$courseId] ?? 0;
                    }
                    $subject->study_num = $studyNum;
                    $subject->course_num = count($courseIds);
                    unset($subject->course);
                }
                $children[$moduleName]['content']['data'] = $subjectList->toarray();
            }
        }
        $data['content']['children'] = $children;
        return $data;
    }

}