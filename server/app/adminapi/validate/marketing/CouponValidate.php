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
namespace app\adminapi\validate\marketing;
use app\common\enum\marketing\CouponEnum;
use app\common\model\course\Course;
use app\common\model\marketing\Coupon;
use app\common\validate\BaseValidate;

/**
 * 优惠券验证器类
 * Class CouponValidate
 * @package app\adminapi\validate\merketing
 */
class CouponValidate extends BaseValidate
{

    protected $rule = [
        'id'                    => 'require',
        'name'                  => 'require|max:64|unique:'.Coupon::class.',name',
        'send_num_type'         => 'require|in:1,2',
        'send_num'              => 'requireIf:send_num_type,2|number|gt:0|elt:1000000',
        'use_time_type'         => 'require|in:'.CouponEnum::USE_TIME_TYPE_FIXED.','.CouponEnum::USE_TYPE_TYPE_TODAY.','.CouponEnum::USE_TYPE_TYPE_MORROW,
        'use_time_start'        => 'requireIf:use_time_type,1',
        'use_time_end'          => 'requireIf:use_time_type,1',
        'use_day'               => 'requireIf:use_time_type,2|requireIf:use_time_type,3',
        'money'                 => 'require|gt:0',
        'get_type'              => 'require|in:'.CouponEnum::GET_TYPE_USER.','.CouponEnum::GET_TYPE_SYSTEM,
        'use_condition'         => 'require|in:'.CouponEnum::USE_CONDITION_NO.','.CouponEnum::USE_CONDITION_MONEY,
        'condition_money'       => 'requireIf:use_condition,'.CouponEnum::USE_CONDITION_MONEY.'|gt:0',
        'get_num_type'          => 'require|in:'.CouponEnum::GET_NUM_TYPE_NO.','.CouponEnum::GET_NUM_TYPE_LIMIT.','.CouponEnum::GET_NUM_TYPE_LIMIT_TODAY,
        'get_num'               => 'requireIf:get_num_type,2|requireIf:get_num_type,3',
        'use_scope'             => 'require|in:'.CouponEnum::USER_SCOPE_NO.','.CouponEnum::USER_SCOPE_COUSE_USABLE.','.CouponEnum::USER_SCOPE_COUSE_NUSABLE.'|checkCourseIds',

        'send_user_num'    => 'require|number|gt:0',
        'send_user'        => 'require|array',
        'receive_time_start'        => 'require',
        'receive_time_end'        => 'require',
    ];


    protected $message = [
        'id.require'                => '请选择优惠券',
        'name.require'              => '请输入优惠券名称',
        'name.max'                  => '优惠券名称不能超过64个字符',
        'name.unique'               => '优惠券名称重复',
//        'is_quota.require'          => '请选择是否限量发放',
        'send_num_type.require'     => '请选择发放数量限制',
        'send_num_type.in'          => '发放数量限制值错误',
        'send_num.requireIf'        => '请输入发放数量',
        'send_num.number'           => '发放数量必须为纯数字',
        'send_num.gt'               => '发放数量必须大于零',
        'send_num.elt'               => '发放数量不能超过1000000',
        'use_time_type.require'     => '请选择使用时间',
        'use_time_type.in'          => '使用时间类型错误',
        'use_time_start.requireIf'  => '请输入开始时间',
        'use_time_end.requireIf'    => '请输入结束时间',
        'use_day.requireIf'         => '请输入天数',
        'money.require'             => '请输入优惠券面额',
        'money.gt'                  => '优惠券面额不能小于等于零',
        'get_type.require'          => '请选择领取方式',
        'get_type.in'               => '领取方式错误',
        'use_condition.require'     => '领取方式错误',
        'condition_money.requireIf' => '请输入使用金额条件',
        'condition_money.gt'        => '使用金额条件不能小于等于零',
        'get_num_type.require'      => '请输入领取次数',
        'get_num_type.in'           => '领取次数类型错误',
        'get_num.requireIf'         => '请输入限领数量',
        'use_scope.require'         => '请选择使用范围',
        'use_scope.in'              => '使用范围类型错误',
        'send_user_num.require' => '请输入每人发放张数',
        'send_user_num.number' => '每人发放张数值错误',
        'send_user_num.gt' => '每人发放张数必须大于0',
        'send_user.require' => '请选择发放用户',
        'send_user.array' => '发放用户值错误',
        'receive_time_start.require' => '请选择领券开始时间',
        'receive_time_end.require' => '请选择领券结束时间',
    ];

    protected function sceneAdd()
    {
        return $this->only(['name','send_num_type','send_num','use_time_type','use_time_start','use_time_end','use_day','money','get_type','use_condition','condition_money','get_num_type','get_num','use_scope','receive_time_start','receive_time_end']);
    }

    protected function sceneEdit()
    {
        return $this->only(['id','name','send_num_type','send_num','use_time_type','use_time_start','use_time_end','use_day','money','get_type','use_condition','condition_money','get_num_type','get_num','use_scope','receive_time_start','receive_time_end']);
    }

    protected function sceneId()
    {
        return $this->only(['id']);
    }

    public function sceneSend()
    {
        return $this->only(['id','send_user_num', 'send_user'])
            ->append('id','array');
    }


    /**
     * @notes 验证课程
     * @param $value
     * @return bool|string
     * @author cjhao
     * @date 2023/4/12 18:52
     */
    public function checkCourseIds($value,$rule,$data)
    {

        if(CouponEnum::USER_SCOPE_NO != $value){
            if(empty($data['course_list'])){
                return '请选择课程';
            }
            $ids =  array_column($data['course_list'],'id');
            $courseNum = Course::where(['id'=>$ids])->count();
            if($courseNum != count($data['course_list'])){
                return '课程信息错误，请重新选择';
            }

        }


        return true;
    }


}