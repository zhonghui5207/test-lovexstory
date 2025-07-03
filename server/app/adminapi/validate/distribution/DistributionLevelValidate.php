<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

namespace app\adminapi\validate\distribution;

use app\common\model\distribution\DistributionLevel;
use app\common\validate\BaseValidate;


class DistributionLevelValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require|checkName',
        'level' => 'require|integer|checkLevel',
        'level_ico' => 'require',
        'level_bg' => 'require',
        'self_ratio' => 'require|between:0,100',
        'first_ratio' => 'require|between:0,100',
        'second_ratio' => 'require|between:0,100|checkRatioSum',
        'upgrade_conditions' => 'require|in:1,2',
        'single_amount' => 'float|egt:0',
        'total_amount' => 'float|egt:0',
        'consume_num' => 'number|egt:0',
        'settled_commission' => 'float|egt:0',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'name.require' => '请输入等级名称',
        'level.require' => '请输入级别',
        'level.integer' => '级别须为整型',
        'level_ico.require' => '请选择等级图标',
        'level_bg.require' => '请选择等级背景图',
        'self_ratio.require' => '请输入自购佣金比例',
        'self_ratio.between' => '自购佣金比例须在0-100之间',
        'first_ratio.require' => '请输入一级佣金比例',
        'first_ratio.between' => '一级佣金比例须在0-100之间',
        'second_ratio.require' => '请输入二级佣金比例',
        'second_ratio.between' => '二级佣金比例须在0-100之间',
        'upgrade_conditions.require' => '请选择升级条件',
        'upgrade_conditions.in' => '升级条件值错误',
        'single_amount.float' => '单笔消费金额值错误',
        'single_amount.egt' => '单笔消费金额必须大于等于0',
        'total_amount.float' => '累计消费金额值错误',
        'total_amount.egt' => '累计消费金额必须大于等于0',
        'consume_num.number' => '累计消费次数值错误',
        'consume_num.egt' => '累计消费次数必须大于等于0',
        'settled_commission.float' => '已结算佣金收入值错误',
        'settled_commission.egt' => '已结算佣金收入必须大于等于0',
    ];


    public function sceneAdd()
    {
        return $this->only(['name','level','level_ico','level_bg','self_ratio','first_ratio','second_ratio','upgrade_conditions','single_amount','total_amount','consume_num','settled_commission']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','level','level_ico','level_bg','self_ratio','first_ratio','second_ratio','upgrade_conditions','single_amount','total_amount','consume_num','settled_commission']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }

    /**
     * @notes 校验等级名称
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/10/20 6:08 下午
     */
    public function checkName($value, $rule, $data)
    {
        $where = [['name', '=', $value]];
        if(isset($data['id'])) {
            // 编辑的场景
            $where[] = ['id', '<>', $data['id']];
        }
        $level = DistributionLevel::where($where)->findOrEmpty();
        if(!$level->isEmpty()) {
            return '等级名称已存在';
        }
        return true;
    }

    /**
     * @notes 校验等级级别
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/10/20 6:09 下午
     */
    public function checkLevel($value, $rule, $data)
    {
        $where = [['level', '=', $value]];
        if(isset($data['id'])) {
            // 编辑的场景
            $where[] = ['id', '<>', $data['id']];
        }
        $level = DistributionLevel::where($where)->findOrEmpty();
        if(!$level->isEmpty()) {
            return '等级级别已存在';
        }
        return true;
    }

    /**
     * @notes 校验佣金总比例
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2023/10/20 6:11 下午
     */
    public function checkRatioSum($value, $rule, $data)
    {
        $selfRatio = $data['self_ratio'] ?: 0;
        $firstRatio = $data['first_ratio'] ?: 0;
        $secondRatio = $data['second_ratio'] ?: 0;
        if ($selfRatio + $firstRatio + $secondRatio > 100) {
            return '等级佣金比例总和不能超过100%';
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
     * @date 2023/10/23 10:33 上午
     */
    public function checkDel($value, $rule, $data)
    {
        $level = DistributionLevel::where('id',$data['id'])->findOrEmpty()->toArray();
        if ($level['is_default'] == 1) {
            return '默认等级，无法删除';
        }
        return true;
    }
}