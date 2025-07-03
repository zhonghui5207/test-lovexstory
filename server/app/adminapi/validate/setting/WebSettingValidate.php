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

namespace app\adminapi\validate\setting;

use app\common\validate\BaseValidate;
use WpOrg\Requests\Requests;

/**
 * 网站设置验证器
 * Class WebSettingValidate
 * @package app\adminapi\validate\setting
 */
class WebSettingValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:30',
        'web_favicon' => 'require',
        'web_logo' => 'require',
        'login_image' => 'require',
        'contact_name' => 'require',
        'contact_phone' => 'require|mobile',
        'document_status' => 'require|in:0,1|checkDocumentStatus',
    ];

    protected $message = [
        'name.require' => '请填写网站名称',
        'name.max' => '网站名称最长为30个字符',
        'web_favicon.require' => '请上传网站图标',
        'web_logo.require' => '请上传网站logo',
        'login_image.require' => '请上传登录页广告图',
        'contact_name.require' => '请输入平台联系人姓名',
        'contact_phone.require' => '请输入平台联系人手机号码',
        'contact_phone.mobile' => '平台联系人手机号码格式错误',
        'document_status.require' => '请选择文档信息开关',
        'document_status.in' => '文档信息值错误',
    ];

    protected $scene = [
        'website' => ['name', 'web_favicon', 'web_logo', 'login_image','contact_name','contact_phone','document_status'],
    ];



    /**
     * @notes 校验产品授权
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/5/16 11:25 上午]
     */
    public function checkDocumentStatus($value,$rule,$data)
    {
        if ($value == 0) {
            $check_domain = config('project.check_domain');
            $product_code = config('project.product_code');
            $domain = $_SERVER['HTTP_HOST'];
            $result = Requests::get($check_domain.'/api/version/productAuth?code='.$product_code.'&domain='.$domain);
            $result = json_decode($result->body,true);
            if (!$result['data']['result']) {
                return '产品未授权，要去官网授权才能操作';
            }
        }

        return true;
    }
}