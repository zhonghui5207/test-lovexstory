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
namespace app\common\enum;
/**
 * 课程枚举
 * Class CourseEnum
 * @package app\common\enum
 */
class CourseEnum
{
    const COURSE_TYPE_IMGAE_TXT = 1;
    const COURSE_TYPE_VOICE     = 2;
    const COURSE_TYPE_VIDEO     = 3;
    const COURSE_TYPE_COLUMN    = 4;

    const FEE_TYPE_FEE      = 1;
    const FEE_TYPE_FREE     = 0;

    const DISTRIBUTION_RULE_DEFAULT = 1;//默认分销等级佣金规则
    const DISTRIBUTION_RULE_CUSTOM = 2;//自定义设置

    /**
     * @notes 获取课程类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/5/20 16:35
     */
    public static function getCouserTypeDesc($from = true)
    {
        $desc = [
            self::COURSE_TYPE_IMGAE_TXT  => '图文',
            self::COURSE_TYPE_VOICE      => '音频',
            self::COURSE_TYPE_VIDEO      => '视频',
//            self::COURSE_TYPE_COLUMN     => '专栏',
        ];
        if(true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';

    }

}