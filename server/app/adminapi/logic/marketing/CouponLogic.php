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
namespace app\adminapi\logic\marketing;
use app\common\enum\marketing\CouponEnum;
use app\common\enum\marketing\CouponListEnum;
use app\common\model\course\Course;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponCourse;
use app\common\model\marketing\CouponList;
use think\Exception;
use think\facade\Db;

/**
 * 优惠券逻辑类
 * Class CouponLogic
 * @package app\adminapi\logic\marketing
 */
class CouponLogic
{

    public function otherLists()
    {

        return [
            'get_type'  => CouponEnum::getGetTypeDesc(),
            'use_scope' => CouponEnum::getUseScopeDesc(),
        ];

    }
    /**
     * @notes 添加优惠券
     * @param array $post
     * @return bool
     * @author cjhao
     * @date 2023/4/11 18:05
     */
    public function add(array $post)
    {
        try{
            Db::startTrans();
            $coupon = new Coupon();
            if(CouponEnum::USE_TIME_TYPE_FIXED == $post['use_time_type']){
                $post['use_time_start'] = strtotime($post['use_time_start']);
                $post['use_time_end'] = strtotime($post['use_time_end']);
            } else {
                $post['use_time_start'] = null;
                $post['use_time_end'] = null;
            }

            //领券时间
            $post['receive_time_start'] = strtotime($post['receive_time_start']);
            $post['receive_time_end'] = strtotime($post['receive_time_end']);

            $coupon->save($post);
            if(CouponEnum::USE_CONDITION_NO != $coupon->use_scope){
                $courseIds = [];
                foreach ($post['course_list'] as $course){
                    $courseIds[] = [
                        'course_id' => $course['id'],
                    ];
                }
                $coupon->courseList()->saveAll($courseIds);
            }
            Db::commit();
            return true;

        }catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }


    /**
     * @notes 编辑优惠券
     * @param array $post
     * @author cjhao
     * @date 2023/4/11 18:55
     */
    public function edit(array $post)
    {

        try{
            Db::startTrans();
            $coupon = Coupon::findOrEmpty($post['id']);
            //已结束的，不允许编辑
            if(CouponEnum::STATUS_END == $coupon->status){
                throw new Exception('优惠券已结束，不允许编辑');
            }
            //进行中，只允许修改名称和发放数量
            if(CouponEnum::STATUS_ING == $coupon->status){
                if ($coupon->send_num_type == CouponEnum::SEND_NUM_TYPE_FIXED) {
                    if ($coupon->send_num > $post['send_num']) {
                        throw new Exception('调整后的发放数量不可少于原来的数量');
                    }
                }
                $coupon->allowField(['name','send_num'])->save($post);
                Db::commit();
                return true;
            }

            if(CouponEnum::USE_CONDITION_NO != $coupon->use_scope){
                CouponCourse::where(['course_id'=>$post['id']])->delete();
            }
            if(CouponEnum::USE_TIME_TYPE_FIXED == $post['use_time_type']){
                $post['use_time_start'] = strtotime($post['use_time_start']);
                $post['use_time_end'] = strtotime($post['use_time_end']);
            } else {
                $post['use_time_start'] = null;
                $post['use_time_end'] = null;
            }

            //领券时间
            $post['receive_time_start'] = strtotime($post['receive_time_start']);
            $post['receive_time_end'] = strtotime($post['receive_time_end']);

            $coupon->save($post);
            if(CouponEnum::USE_CONDITION_NO != $coupon->use_scope){
                $courseIds = [];
                foreach ($post['course_list'] as $course){
                    $courseIds[] = [
                        'course_id' => $course['id'],
                    ];
                }
                $coupon->courseList()->saveAll($courseIds);
            }
            Db::commit();
            return true;

        }catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }


    /**
     * @notes 获取优惠券详情
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2023/4/12 10:56
     */
    public function detail(int $id):array
    {
        $coupon = Coupon::where(['id'=>$id])->with(['course_list'])->withoutField(['create_time','update_time','delete_time'])->findOrEmpty()->toArray();
        $coupon['is_quota'] = 0;
        if(0 == $coupon['send_num']){
            $coupon['is_quota'] = 1;
        }
        if($coupon['course_list']){
            $courseIds = array_column($coupon['course_list'],'course_id');
            $courseList = Course::where(['id'=>$courseIds])
                ->with(['teacherName'])
                ->field('id,name,cover,type,teacher_id,sell_price,status,is_choice')
                ->append(['type_desc'])
                ->select()->toArray();

            $coupon['course_list'] = $courseList;
        }

        //领券时间
        $coupon['receive_time_start'] = date('Y-m-d H:i:s',$coupon['receive_time_start']);
        $coupon['receive_time_end'] = date('Y-m-d H:i:s',$coupon['receive_time_end']);
        return $coupon;
    }


    /**
     * @notes 修改优惠券状态
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2023/4/12 15:56
     */
    public function status(int $id)
    {
        $coupon = Coupon::findOrEmpty($id);
        if(CouponEnum::STATUS_END == $coupon->status){
            return '优惠券已结束';
        }
        $coupon->status = $coupon->status+1;
        $coupon->save();
        return true;
    }



    /**
     * @notes 删除优惠券
     * @param int $id
     * @return bool|string
     * @author cjhao
     * @date 2023/4/12 18:44
     */
    public function del(int $id)
    {
        try{
            Db::startTrans();
            $coupon = Coupon::findOrEmpty($id);
            if(CouponEnum::STATUS_END != $coupon->status){
                return '请先将优惠券结束掉在删除';
            }
            $coupon->delete();
            //将用户优惠券作废掉
            CouponList::where(['coupon_id'=>$id])->update(['status'=>CouponListEnum::STATUS_CANCEL]);
            Db::commit();
            return true;

        }catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }


    /**
     * @notes 发放优惠券
     * @param $params
     * @return bool|string
     * @throws \Exception
     * @author ljj
     * @date 2023/8/28 11:34 上午
     */
    public static function send($params)
    {
        try {
            foreach ($params['id'] as $id) {
                // 获取优惠券库存信息
                $coupon = (new Coupon())->findOrEmpty((int)$id)->toArray();

                if ($coupon['status'] == CouponEnum::STATUS_NOT_START) {
                    return '优惠券活动尚未开始,不能发放';
                }

                if ($coupon['status'] == CouponEnum::STATUS_END) {
                    return '优惠券活动已结束,不能发放';
                }

                if ($coupon['get_type'] != CouponEnum::GET_TYPE_SYSTEM) {
                    return '非系统赠送的优惠券，不能发放';
                }

                $totalSendNum = $params['send_user_num'] * count($params['send_user']);
                if ($coupon['send_num_type'] == CouponEnum::SEND_NUM_TYPE_FIXED) {
                    $send_total = CouponList::where(['coupon_id'=>$coupon['id']])->count();
                    if ($totalSendNum > ($coupon['send_num'] - $send_total)) {
                        return '发放的总数量,超出库存数量,不能发放';
                    }
                }

                // 计算出券最后可用时间
                $invalid_time = 0;
                switch ($coupon['use_time_type']) {
                    case CouponEnum::USE_TIME_TYPE_FIXED:
                        $invalid_time = strtotime($coupon['use_time_end']);
                        break;
                    case CouponEnum::USE_TYPE_TYPE_TODAY:
                        $invalid_time = time() + ($coupon['use_day'] * 86400);
                        break;
                    case CouponEnum::USE_TYPE_TYPE_MORROW:
                        $time = strtotime(date('Y-m-d', strtotime("+1 day")));
                        $invalid_time = $time + ($coupon['use_day'] * 86400);
                }


                // 指定用户发放
                $couponListModel = new CouponList();
                foreach ($params['send_user'] as $user_id) {
                    $list = [];
                    for ($i=1; $i<=$params['send_user_num']; $i++) {
                        $list[] = [
                            'channel'      => CouponEnum::GET_TYPE_SYSTEM,
                            'admin_id'  => $params['admin_id'],
                            'user_id'      => $user_id,
                            'coupon_id'    => $coupon['id'],
                            'invalid_time' => $invalid_time,
                        ];
                    }
                    $couponListModel->saveAll($list);
                }
            }

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}