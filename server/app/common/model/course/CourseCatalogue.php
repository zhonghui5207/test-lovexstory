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
use app\common\model\BaseModel;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

/**
 * 课程目录模型类
 * Class CourseCatalogue
 * @package app\common\model\course
 */
class CourseCatalogue extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


//    /**
//     * @notes 公共图片处理,去除图片域名
//     * @param $value
//     * @return mixed|string
//     * @author 张无忌
//     * @date 2021/9/10 11:04
//     */
//    public function setContentAttr($value)
//    {
//        return trim($value) ? FileService::setFileUrl($value) : '';
//    }


//    /**
//     * @notes 公共图片处理,图片域名
//     * @param $value
//     * @return mixed|string
//     * @author cjhao
//     * @date 2022/12/2 16:57
//     */
//    public function getContentAttr($value)
//    {
//        return trim($value) ? FileService::getFileUrl($value) : '';
//    }

}