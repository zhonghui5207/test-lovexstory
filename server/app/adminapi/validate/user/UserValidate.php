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
namespace app\adminapi\validate\user;

use app\common\model\user\User;
use app\common\validate\BaseValidate;
use think\facade\Validate;
use think\validate\ValidateRule;

/**
 * 用户验证类
 * Class UserValidate
 * @package app\adminapi\validate\user
 */
class UserValidate extends BaseValidate
{

    protected $rule = [
        'id'            => 'require|checkUser',
        'field'         => 'require|checkField',
        'value'         => 'require',
        'money'         => 'require',
        'action'        => 'require|in:0,1',
        'remark'        => 'require',
        'course_id'     => 'require',
        'wallet_type'   => 'require|in:1,2',

    ];

    protected $message = [
        'id.require'            => '请选择用户',
        'field.require'         => '请选择修改的信息',
        'value.require'         => '请输入修改的值',
        'money.require'         => '请输入调整余额',
        'action.require'        => '请选择调整类型',
        'action.in'             => '调整类型错误',
        'remark.require'        => '请输入备注',
        'course_id.require'     => '请选择课程',
        'wallet_type.require'   => '请选择金额类型',
        'wallet_type.in'        => '金额类型值错误',
    ];

    protected function sceneDetail()
    {
        return $this->only(['id']);
    }

    protected function sceneSetInfo()
    {
        return $this->only(['id', 'field', 'value']);
    }

    protected function sceneAdjustWallet()
    {
        return $this->only(['id','wallet_type','money','action']);
    }

    protected function sceneRecycleCourse()
    {
        return $this->only(['id','course_id']);
    }

    protected function checkUser($value, $rule, $data)
    {
        if ((User::findOrEmpty($value))->isEmpty()) {
            return '用户不存在';
        }

        return true;
    }


    public function checkField($value, $rule, $data)
    {
        $allowField = ['real_name', 'sex', 'mobile','account'];
        if (!in_array($value, $allowField)) {
            return '信息不允许修改';
        }
        switch ($value) {
            //验证手机号码是否存在
            case 'mobile':
                if(empty($data['value'])){
                    return '手机号码不能为空';
                }
                $checkRule = Validate::checkRule($data['value'], 'mobile');
                if(!$checkRule){
                    return '手机号码格式错误';
                }
                $mobile = User::where('id', '<>', $data['id'])
                    ->where('mobile', '=', $data['value'])
                    ->findOrEmpty();

                if (!$mobile->isEmpty()) {
                    return '联系电话已被使用';
                }
                break;
            case 'sex':
                if(empty($data['value'])){
                    return '性别不能为空';
                }
                if (!in_array($data['value'], [0, 1, 2])) {
                    return '性别信息错误';
                }
                break;
            case 'account':
                if(empty($data['value'])){
                    return '账号不能为空';
                }
                $checkRule = Validate::checkRule($data['value'], 'regex:^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$');
                if(!$checkRule){
                    return '账号须为字母数字组合';
                }
                $user = User::where([
                    ['account', '=', $data['value']],
                    ['id', '<>', $data['id']]
                ])->findOrEmpty();
                if (!$user->isEmpty()) {
                    return '账号已被使用!';
                }
                break;


        }
        return true;
    }



}