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
namespace app\adminapi\validate\order;
use app\common\validate\BaseValidate;

/**
 * 订单验证类
 * Class OrderValidate
 * @package app\adminapi\validate\order
 */
class OrderValidate extends BaseValidate
{
    protected $rule = [
        'id'            => 'require',
        'remark'        => 'require|length:0,64'
    ];

    protected $message = [
        'id.require'        => '请选择订单',
        'remark.require'    => '请输入商家备注',
        'remark.length'     => '商家备注不能超过64个字符',
    ];
    protected function sceneId()
    {
        return $this->only(['id']);
    }

    protected function sceneRemark()
    {
        return $this->only(['id','remark']);
    }

}