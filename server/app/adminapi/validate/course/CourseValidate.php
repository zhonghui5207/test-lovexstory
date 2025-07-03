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
use app\common\enum\OrderEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseCategory;
use app\common\model\course\StudyCourse;
use app\common\model\order\Order;
use app\common\model\teacher\Teacher;
use app\common\validate\BaseValidate;

/**
 * 课程验证类
 * Class CourseValidate
 * @package app\adminapi\validate\course
 */
class CourseValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'type' => 'require|in:' . CourseEnum::COURSE_TYPE_IMGAE_TXT . ',' . CourseEnum::COURSE_TYPE_VOICE . ',' . CourseEnum::COURSE_TYPE_VIDEO . ',' . CourseEnum::COURSE_TYPE_COLUMN,
        'name' => 'require|max:128|unique:' . Course::class . ',name',
        'category_id' => 'require|checkCategory',
        'teacher_id' => 'require|checkTeacher',
        'cover' => 'require',
        'images' => 'require|array',
        'synopsis'  => 'max:254',
        'content' => 'require',
        'fee_type' => 'require|in:0,1',
        'sell_price' => 'requireIf:fee_type,1|checkSellPrice',
        'line_price' => 'checkLinePrice',
        'virtual_study_num' => 'egt:0',
        'status' => 'require|in:0,1',
        'is_choice' => 'require|in:0,1',
        'course_ids' => 'require',
    ];

    protected $message = [
        'name.require' => '请输入课程名称',
        'name.unique' => '课程名称重复',
        'name.max' => '课程名称不能超过128个字符',
        'type.require' => '请选择课程类型',
        'type.in' => '课程类型错误',
        'synopsis.max'  => '课程简介不能超过254个字符',
        'category_id.require' => '请选择课程分类',
        'teacher_id.require' => '请选择讲师',
        'cover.require' => '请上传课程封面',
        'images.require' => '请上传轮播图',
        'images.array' => '轮播图数据错误',
        'content.require' => '请填写课程详情',
        'fee_type.require' => '请选择收费方式',
        'fee_type.in' => '收费方式错误',
        'sell_price.requireIf' => '请输入售价',
        'sell_price.gt' => '售价不能小于零',
        'line_price.requireIf' => '请输入划线价',
        'line_price.gt' => '划线价不能小于零',
        'virtual_study_num.egt' => '虚拟学习人数不能小于零',
        'status.require' => '请选择课程状态',
        'status.in' => '课程状态错误',
        'status.array' => '专栏目录错误',
        'is_choice.require' => '请选择是否精选',
        'is_choice.in' => '精选值错误',
    ];

    public function sceneAdd()
    {
        return $this->remove(['id' => 'require', 'course_ids' => 'require']);
    }

    public function sceneEdit()
    {
        return $this->remove(['course_ids' => 'require']);
    }

    public function sceneColumn()
    {
        return $this->only(['course_ids']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneStatus()
    {
        return $this->only(['id', 'status']);
    }

    public function sceneChoice()
    {
        return $this->only(['id', 'is_choice']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])->append('id','checkDel');
    }

    public function checkCategory($value, $rule, $data)
    {
        $category = CourseCategory::where(['id' => $value])->findOrEmpty();
        if ($category->isEmpty()) {
            return '课程分类不存在';
        }
//        if (1 == $category->level) {
//            return '课程分类必须选到二级';
//        }
        return true;
    }

    public function checkTeacher($value, $rule, $data)
    {
        $teacher = Teacher::where(['id' => $value])->findOrEmpty();
        if ($teacher->isEmpty()) {
            return '讲师不存在，请重新选择';
        }
        return true;
    }

    public function checkSellPrice($value, $rule, $data)
    {
        if (0 == $data['fee_type']) {
            return true;
        }
        if ($value <= 0) {
            return '价格必须大于零';
        }
        return true;
    }

    public function checkLinePrice($value, $rule, $data)
    {

        if (0 == $data['fee_type']) {
            return true;
        }
        if ($value < 0) {
            return '划线不能小于零';
        }
        return true;
    }


    /**
     * @notes 校验删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/8/16 11:28 上午
     */
    public function checkDel($value,$rule,$data)
    {
        if (is_array($value)) {
            foreach ($value as $id) {
                $course = Course::findOrEmpty($id);
                if($course->isEmpty()){
                    return '课程不存在';
                }

                $order = Order::alias('O')
                    ->where(['order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE])
                    ->join('order_course OC', 'O.id = OC.order_id')
                    ->where(['OC.course_id' => $id])
                    ->field('O.id')
                    ->findOrEmpty();

                if(!$order->isEmpty()){
                    return '已有用户购买了该课程，不允许删除';
                }

                //如果有人加入学习，不行允许删除
                $studyCourse = StudyCourse::where(['course_id' => $id])->findOrEmpty();
                if(!$studyCourse->isEmpty()){
                    return '该课程已经有人在学习中，不允许删除';
                }
            }
        } else {
            $course = Course::findOrEmpty($value);
            if($course->isEmpty()){
                return '课程不存在';
            }

            $order = Order::alias('O')
                ->where(['order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE])
                ->join('order_course OC', 'O.id = OC.order_id')
                ->where(['OC.course_id' => $value])
                ->field('O.id')
                ->findOrEmpty();

            if(!$order->isEmpty()){
                return '已有用户购买了该课程，不允许删除';
            }

            //如果有人加入学习，不行允许删除
            $studyCourse = StudyCourse::where(['course_id' => $value])->findOrEmpty();
            if(!$studyCourse->isEmpty()){
                return '该课程已经有人在学习中，不允许删除';
            }
        }


        return true;
    }

}