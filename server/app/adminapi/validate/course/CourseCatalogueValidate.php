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
namespace app\adminapi\validate\course;
use app\common\enum\CourseEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseCatalogue;
use app\common\validate\BaseValidate;

/**
 * 课程目验证类
 * Class CourseCatalogueValidate
 * @package app\adminapi\validate\course
 */
class CourseCatalogueValidate extends BaseValidate
{
    protected $rule = [
        'id'            => 'require|checkCatalogue',
        'course_id'     => 'require|checkCourse',
        'name'          => 'require|max:64|checkName',
        'fee_type'      => 'require|in:0,1',
        'status'        => 'require|in:0,1',
        'sort'          => 'egt:0',
        'content'       => 'require',
    ];

    protected $message = [
        'id.require'        => '请选择目录',
        'course_id.require' => '课程id缺少',
        'name.require'      => '请输入目录名称',
        'name.max'          => '目录名称最多只能输入64个字符',
        'fee_type.require'  => '请选择收费类型',
        'fee_type.in'       => '收费类型错误',
        'sort.egt'          => '排序必须大于零',
        'status.require'    => '请选择状态',
        'status.in'         => '状态类型错误',
        'content.require'   => '请输入内容',
    ];


    public function sceneAdd(){
        return $this->remove('id','require');
    }


    public function sceneEdit()
    {
        return $this->remove('course_id','require|checkCourse');
    }

    public function sceneFee()
    {
        return $this->only(['id','fee_type']);
    }

    public function sceneStatus()
    {
        return $this->only(['id','status'])->remove('id','checkCatalogue');
    }

    public function sceneDel()
    {
        //todo 删除验证
        return $this->only(['id'])->remove('id','checkCatalogue');
    }

    public function sceneDetail()
    {
        return $this->only(['id'])->remove('id','checkCatalogue');
    }


    protected function checkId($value,$rule,$data)
    {

        $courseCatalogue = CourseCatalogue::findOrEmpty($value);
        if($courseCatalogue->isEmpty()){
            return '目录不存在';
        }
        return true;

    }

    protected function checkCourse($value,$rule,$data)
    {
        $course = Course::findOrEmpty($value);
        if($course->isEmpty()){
            return '当前课程不存在';
        }
        return true;
    }

    protected function checkName($value,$rule,$data)
    {
        $where = [];
        $where[] = ['name','=',$value];

        if(isset($data['id'])){
            $where[] = ['id','<>',$data['id']];
            $courseId = CourseCatalogue::where(['id'=>$data['id']])->value('course_id');
            $where[] = ['course_id','=',$courseId];
        }else{
            $where[] = ['course_id','=',$data['course_id']];
        }
        $courseCatalogue = CourseCatalogue::where($where)->findOrEmpty();
        if(!$courseCatalogue->isEmpty()){
            return '目录名称已存在';
        }
        return true;

    }

    protected function checkCatalogue($value,$rule,$data)
    {
        $feeType = Course::alias('C')
            ->join('course_catalogue CC','C.id = CC.course_id')
            ->where(['CC.id'=>$value])
            ->value('C.fee_type');

        if(CourseEnum::FEE_TYPE_FREE == $feeType && CourseEnum::FEE_TYPE_FEE == $data['fee_type']){
            return '当前课程是免费，目录不允许设置成收费';
        }
        return true;
    }
}
