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
namespace app\common\model\course;
use app\common\enum\CourseEnum;
use app\common\enum\CourseImage;
use app\common\model\BaseModel;
use app\common\model\teacher\Teacher;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

/**
 * 课程模型
 * Class Course
 * @package app\common\model\cource
 */
class Course extends BaseModel
{

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function teacherName()
    {
        return $this->hasOne(Teacher::class,'id','teacher_id')
            ->bind(['teacher_name'=>'name']);
    }
    public function teacher()
    {
        return $this->hasOne(Teacher::class,'id','teacher_id');
    }

    public function catalogue()
    {
        return $this->hasMany(CourseCatalogue::class,'course_id')
                ->where(['status'=>1])
                ->order('sort desc')
                ->field('id,course_id,name,fee_type,cover,duration');
    }

    public function meterial()
    {
        return $this->hasOne(CourseMaterial::class,'course_id')
                ->field('id,course_id,content,link,code,is_link');
    }

    public function course()
    {
        return $this->hasMany(CourseColumn::class,'course_id')
                ->field('id,course_id,relation_id,fee_type');
    }

    public function comment()
    {
        return $this->hasMany(CourseComment::class,'course_id')
                ->field('id,course_id,user_id,course_score,comment,create_time')
                ->order('id desc');
    }

    /**
     * @notes 公共处理图片,补全路径
     * @param $value
     * @return string
     * @author 张无忌
     * @date 2021/9/10 11:02
     */
    public function getCoverAttr($value)
    {
        return trim($value) ? FileService::getFileUrl($value) : '';
    }

    /**
     * @notes 公共图片处理,去除图片域名
     * @param $value
     * @return mixed|string
     * @author 张无忌
     * @date 2021/9/10 11:04
     */
    public function setCoverAttr($value)
    {
        return trim($value) ? FileService::setFileUrl($value) : '';
    }

    public function courseImage()
    {
        return $this->hasMany(CourseImage::class,'course_id');
    }


    public function getImagesAttr()
    {
        $images = array_column($this->courseImage->toArray(), 'uri');
        return $images;
    }

    public function getTypeDescAttr($value,$data)
    {
        return CourseEnum::getCouserTypeDesc($data['type']);
    }

    /**
     * @notes 课程名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @author cjhao
     * @date 2022/5/27 16:44
     */
    public function searchNameAttr($query,$value,$data)
    {
        $value && $query->where('name','like','%'.$value.'%');
    }

    /**
     * @notes 排除当前课程
     * @param $query
     * @param $value
     * @param $data
     * @author cjhao
     * @date 2022/5/30 14:25
     */
    public function searchIdAttr($query,$value,$data)
    {
        $value && $query->where('id','<>',$value);
    }

    /**
     * @notes 类型搜索
     * @param $query
     * @param $value
     * @param $data
     * @author cjhao
     * @date 2022/5/30 14:33
     */
    public function searchTypeAttr($query,$value,$data)
    {
        $value && $query->where('type','=',$value);
    }

    /**
     * @notes 课程分类搜索器
     * @param $query
     * @param $value
     * @param $data
     * @author cjhao
     * @date 2022/5/27 16:44
     */
    public function searchCategoryIdAttr($query,$value,$data)
    {
        $value && $query->where('category_id','=',$value);
    }

    /**
     * @notes 课程目录统计
     * @param $query
     * @param $data
     * @return int
     * @author ljj
     * @date 2023/8/28 11:13 上午
     */
    public function getCatalogueCountAttr($value,$data)
    {
        return CourseCatalogue::where(['course_id'=>$data['id'],'status'=>1])->count();
    }
}