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
namespace app\common\validate;
/**
 * 文件上传验证类
 * Class UploadValidate
 * @package app\common\validate
 */
class UploadValidate extends BaseValidate
{

    protected $rule = [
        'file'              => 'fileExt:jpg,jpeg,gif,png,bmp,tga,tif,pdf,psd,avi,mp3,mp4,wmv,mpg,mpeg,3gp,mov,rm,ram,swf,flv,zip,rar',
    ];

    protected $message = [
        'file.fileExt'      => '该文件类型不允许上传',
    ];

}