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

namespace app\common\enum;


class UserEnum
{
    //来源
    const WECHAT_MMP = 1; //微信小程序
    const WECHAT_OA  = 2; //微信公众号
    const H5         = 3;//手机H5登录
    const PC         = 4;//电脑PC
    const IOS        = 5;//苹果app
    const ANDROID    = 6;//安卓app

    const ALL_TERMINAL = [
        self::WECHAT_MMP,
        self::WECHAT_OA,
        self::H5,
        self::PC,
        self::IOS,
        self::ANDROID,
    ];


    //用户性别
    const UNKNOWN = 0;//未知
    const MAN = 1;//男
    const WOMAN = 2;//女


    /**
     * @notes 获取终端
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2022/2/8 9:26 上午
     */
    public static function getSourceDesc($from = true)
    {
        $desc = [
            self::WECHAT_MMP    => '微信小程序',
            self::WECHAT_OA     => '微信公众号',
            self::H5            => '手机H5',
            self::PC            => '电脑PC',
            self::IOS           => '苹果APP',
            self::ANDROID       => '安卓APP',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '未知';
    }


    /**
     * @notes 用户性别
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2022/2/7 6:49 下午
     */
    public static function getSexDesc($value = true)
    {
        $data = [
            self::UNKNOWN => '未知',
            self::MAN => '男',
            self::WOMAN => '女'
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }
}