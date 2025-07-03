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
namespace app\adminapi\logic\user;
use app\common\enum\AccountLogEnum;
use app\common\enum\marketing\CouponEnum;
use app\common\enum\OrderEnum;
use app\common\logic\AccountLogLogic;
use app\common\logic\CourseLogic;
use app\common\model\AccountLog;
use app\common\model\course\StudyCourse;
use app\common\model\marketing\CouponList;
use app\common\model\order\Order;
use app\common\model\user\User;
use think\Exception;
use think\facade\Db;

/**
 * 用户逻辑类
 * Class UserLogic
 * @package app\adminapi\logic\user
 */
class UserLogic
{

    /**
     * @notes 获取用户信息
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2022/5/24 16:15
     */
    public function detail(int $id):array
    {
        $user = User::withoutField('password,update_time,delete_time')->append(['first_leader_name'])->findOrEmpty($id);
        $sex = $user->getData('sex');
        $user = $user->toArray();
        $user['sex'] = $sex;
        $studyCourse = StudyCourse::where(['user_id'=>$id])
                ->with(['course'=>function($query){
                    $query->bind(['id','cover','name','type']);
                }])
                ->order('id desc')
                ->field('source_id,source_id,course_id,create_time')
                ->select();
        $orderIds = array_column($studyCourse->toArray(),'source_id');

        $orderLits = Order::alias('O')
            ->join('order_course OC','O.id = OC.order_id')
            ->where(['O.id'=>$orderIds])
            ->column('order_amount','course_id');
        foreach ($studyCourse as $course)
        {
            $orderAmount = $orderLits[$course->course_id] ?? 0;
            $course->free = $orderAmount > 0 ? 1 : 0;

        }
        $user['study_course_lists'] = $studyCourse->toArray();

        //优惠券数量
        $user['coupon_num'] = CouponList::where(['user_id'=>$id,'status'=>CouponEnum::USE_STATUS_NOT])->count();

        //账户总金额
        $user['total_funds'] = round($user['user_money'] + $user['user_earnings'],2);

        return $user;
    }

    /**
     * @notes 设置用户信息
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/5/24 16:49
     */
    public function setInfo(array $params):bool
    {
        $user = new User();
        $user->allowField(['real_name','sex','mobile','account'])
            ->where(['id'=>$params['id']])
            ->update([$params['field']=>$params['value']]);
        return true;

    }

    /**
     * @notes 调整用户钱包
     * @param array $params
     * @return bool|string
     * @author cjhao
     * @date 2022/5/24 18:20
     */
    public function adjustUserWallet(array $params)
    {
        try{
            Db::startTrans();
            $money = $params['money'];
            $user = User::find($params['id']);
            switch ($params['wallet_type']){
                case 1://余额
                    //扣减余额
                    if(0 == $params['action']){
                        $surplusMoney = round($user->user_money - $money,2);
                        if($surplusMoney < 0){
                            throw new Exception('余额仅剩:'.$user->user_money);
                        }

                        $user->user_money = $surplusMoney;
                        $type = AccountLogEnum::BNW_INC_ADMIN;
                    }else{

                        $user->user_money = $user->user_money + $money;
                        $type = AccountLogEnum::BNW_DEC_ADMIN;
                    }
                    $user->save();

                    AccountLogLogic::record($user->id,AccountLogEnum::BNW,$type,$params['action'],$money,'',$params['remark']??'');
                    break;
                case 2://可提现佣金
                    //增加
                    if(0 == $params['action']){
                        $surplusMoney = round($user->user_earnings - $money,2);
                        if($surplusMoney < 0){
                            throw new Exception('可提现佣金仅剩:'.$user->user_earnings);
                        }

                        $user->user_earnings = $surplusMoney;
                        $type = AccountLogEnum::EAR_DEC_ADMIN;
                    }else{
                        $user->user_earnings = $user->user_earnings + $money;
                        $type = AccountLogEnum::EAR_INC_ADMIN;
                    }
                    $user->save();

                    AccountLogLogic::record($user->id,AccountLogEnum::EAR,$type,$params['action'],$money,'',$params['remark']??'');
                    break;
            }

            Db::commit();
            return true;
        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();

        }

    }

    /**
     * @notes 回收课程
     * @param array $params
     * @return bool|string
     * @author cjhao
     * @date 2022/6/22 11:06
     */
    public function recycleCourse(array $params)
    {
        try{
            Db::startTrans();
            $sutdyCourse = StudyCourse::where(['user_id'=>$params['id'],'course_id'=>$params['course_id']])
                ->findOrEmpty();

            if($sutdyCourse->isEmpty()){
                throw new Exception('学习课程已被回收');
            }
            CourseLogic::courseStatistics($params['id'],$params['course_id'],$sutdyCourse['order_id'] ?? 0,0);

            Db::commit();
            return true;


        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();
        }


    }

}