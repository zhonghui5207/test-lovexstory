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

namespace app\adminapi\logic;


use app\common\enum\OrderEnum;
use app\common\logic\BaseLogic;
use app\common\model\course\StudyCourse;
use app\common\model\IndexVisit;
use app\common\model\order\Order;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 工作台
 * Class WorkbenchLogic
 * @package app\adminapi\logic
 */
class WorkbenchLogic extends BaseLogic
{
    /**
     * @notes 工作套
     * @param $adminInfo
     * @return array
     * @author 段誉
     * @date 2021/12/29 15:58
     */
    public static function index()
    {
        return [
            // 版本信息
            'platform' => self::versionInfo(),
            // 今日数据
            'today' => self::today(),
            // 常用功能
            'menu' => self::menu(),
            // 近15日访客数
            'chart' => self::visitor(),
        ];
    }


    /**
     * @notes 常用功能
     * @return array[]
     * @author 段誉
     * @date 2021/12/29 16:40
     */
    public static function menu() : array
    {
        return [
            [
                'name' => '图文课程',
                'image' => FileService::getFileUrl(config('project.default_image.course_text')),
                'url' => '/course/course/imageText'
            ],
            [
                'name' => '视频课程',
                'image' => FileService::getFileUrl(config('project.default_image.course_video')),
                'url' => '/course/course/video'
            ],
            [
                'name' => '音频课程',
                'image' => FileService::getFileUrl(config('project.default_image.course_audio')),
                'url' => '/course/course/audio'
            ],
            [
                'name' => '讲师管理',
                'image' => FileService::getFileUrl(config('project.default_image.teacher')),
                'url' => '/teacher/index'
            ],
            [
                'name' => '订单管理',
                'image' => FileService::getFileUrl(config('project.default_image.order')),
                'url' => '/order/index'
            ],
            [
                'name' => '专区管理',
                'image' => FileService::getFileUrl(config('project.default_image.subject')),
                'url' => '/app/subject'
            ],
            [
                'name' => '财务中心',
                'image' => FileService::getFileUrl(config('project.default_image.summarize')),
                'url' => '/finance/summarize'
            ],
            [
                'name' => '渠道设置',
                'image' => FileService::getFileUrl(config('project.default_image.channel')),
                'url' => '/channel/wx_oa/config'
            ],
        ];
    }


    /**
     * @notes 版本信息
     * @return array
     * @author 段誉
     * @date 2021/12/29 16:08
     */
    public static function versionInfo() : array
    {
        return [
            'version' => config('project.version'),
            'website' => config('project.website.url'),
            'name' => ConfigService::get('website', 'name'),
        ];
    }


    /**
     * @notes 今日数据
     * @return int[]
     * @author 段誉
     * @date 2021/12/29 16:15
     */
    public static function today() : array
    {
        //今日营业额
        $todayOrderAmount = Order::where('pay_status', OrderEnum::PAY_STATUS_PAY)
                    ->whereDay('create_time')
                    ->sum('order_amount');
        //总营业额
        $totalOrderAmount = Order::where('pay_status', OrderEnum::PAY_STATUS_PAY)
                    ->sum('order_amount');
        //今日订单笔数
        $todayOrderNum = Order::where('pay_status', OrderEnum::PAY_STATUS_PAY)
                    ->whereDay('create_time')
                    ->count();
        //总订单笔数
        $totalOrderNum = Order::where('pay_status', OrderEnum::PAY_STATUS_PAY)
                    ->count();

        //今日访问数
        $todayVisitor = IndexVisit::whereDay('create_time')->group('ip')->count();
        //总访问数
        $totalVisitor = IndexVisit::group('ip')->count();
        // 新增用户
        $todayNewUser = User::whereDay('create_time')->count();
        //总用户
        $totalUser = User::count();
        //新增的学员
        $todayStudent = StudyCourse::whereDay('create_time')->group('user_id')->count();
        $totalStudent = StudyCourse::group('user_id')->count();

        return [
            'now'                   => date('Y-m-d H:i:s'),
            'today_order_amount'    => $todayOrderAmount,       //今日营业额
            'total_order_amount'    => $totalOrderAmount,       //总营业额
            'today_order_num'       => $todayOrderNum,          //今日订单笔数
            'total_order_num'       => $totalOrderNum,          //总订单笔数
            'today_student'         => $todayStudent,           //新增学员
            'total_student'         => $totalStudent,           //总会员量
            'today_visitor'         => $todayVisitor,           //今日访问量
            'total_visitor'         => $totalVisitor,           //总访问量
            'today_new_user'        => $todayNewUser,           //新增用户
            'total_user'            => $totalUser,              //总用户

        ];
    }


    /**
     * @notes 访问数
     * @return array
     * @author 段誉
     * @date 2021/12/29 16:57
     */
    public static function visitor() : array
    {

        for($i = 15; $i > 0; $i--) {
            $day = new \DateTime('-'.$i.'days');
            $targetDay = $day->format('n.d');
            $startTime = strtotime($day->format('y-m-d'));
            $endTime = $startTime+86399;
            //营业额

            $orderAmount = Order::whereBetween('create_time',"$startTime,$endTime")
                ->where('pay_status','=',OrderEnum::PAY_STATUS_PAY)
                ->sum('order_amount');
            $visvit = IndexVisit::whereBetween('create_time',"$startTime,$endTime")
                ->group('ip')
                ->count();

            $turnover[$targetDay] = $orderAmount;
            $visitor[$targetDay] = $visvit;

        }

        return [
            'turnover'  => $turnover,
            'visitor'   => $visitor,
        ];
    }

}