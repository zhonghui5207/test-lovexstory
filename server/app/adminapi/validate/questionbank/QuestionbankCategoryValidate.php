<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\adminapi\validate\questionbank;


use app\common\validate\BaseValidate;

class QuestionbankCategoryValidate extends BaseValidate
{
    protected $rule = [
        'id'        => 'require',
        'name'      => 'require',
        'sort'      => 'number|max:5',
    ];

    protected $message = [
        'id.require'        => '请选择分类',
        'name.require'      => '分类名称不能为空',
        'sort.number'       => '排序必须为纯数字',
        'sort.max'          => '排序最大不能超过五位数',
    ];

    public function sceneAdd()
    {
        return $this->only(['name','sort']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','sort']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }


    public function checkDel($value,$rule,$data)
    {
        //TODO 已绑定题库的分类不能删除
        return true;
    }
}