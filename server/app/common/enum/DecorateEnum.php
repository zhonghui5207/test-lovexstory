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
 * 装修枚举类
 * Class ThemeEnum
 * @package app\common\enum
 */
class DecorateEnum
{
    const TYPE_INDEX         = 1;
    const TYPE_CENTER        = 2;
    const TYPE_NAVIGATION    = 3;

    //组件
    const SPECIAL       = 'special';        //专题组件

    /**
     * @notes 获取类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/6/27 18:11
     */
    public function getTypeDesc($from = true){
        $desc = [
            self::TYPE_INDEX         => '首页装修',
            self::TYPE_CENTER        => '个人中心',
            self::TYPE_NAVIGATION    => '底部导航栏'
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }


}