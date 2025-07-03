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
namespace app\adminapi\validate\decorate;
use app\common\model\decorate\Subject;
use app\common\validate\BaseValidate;

/**
 * 专题专区验证器
 * Class SubjectValidate
 * @package app\adminapi\validate\decorate
 */
class SubjectValidate extends BaseValidate
{

    protected $rule = [
        'id'            => 'require|checkSubject',
        'name'          => 'require|max:32|unique:'. Subject::class .',name|checkName',
        'cover'         => 'require',
        'status'        => 'require|in:0,1',
    ];

    protected $message = [
        'id.require'            => '请选择专区',
        'name.require'          => '请输入专区名称',
        'name.unique'           => '专区名称已存在',
        'name.max'              => '专区名称不能超过32个字符',
        'cover.require'         => '请上传封面',
        'status.require'        => '请选择专区状态',
        'status.in'             => '状态错误',
    ];

    protected function sceneAdd()
    {
        return $this->remove('id','require|checkSubject');
    }

//    protected function scenediEt()
//    {
//        return $this->only(['id','name','cover','status']);
//    }

    protected function sceneDetail()
    {
        return $this->only(['id']);
    }
    protected function sceneDel()
    {
        return $this->only(['id']);
    }

    protected function sceneStatus()
    {
        return $this->only(['id','status']);
    }

    protected function checkSubject($value,$rule,$data)
    {
        if(Subject::findOrEmpty($value)){
            return true;
        };
        return '专区不存在';
    }

    public function checkName($value,$rule,$data)
    {
        $name = trim($value);
        if(empty($name)){
            return '专区名称不能为空';
        }
        return true;
    }

}