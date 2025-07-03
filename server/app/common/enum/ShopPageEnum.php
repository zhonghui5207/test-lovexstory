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
 * 商城页面枚举类
 * Class ShopPageEnum
 * @package app\common\enum
 */
class ShopPageEnum
{
    //首页
    const HOME_PAGE         = [
        'mobile'        => '/pages/index/index'
    ];
    //分类
    const CATEGORY_PAGE     = [
        'mobile'        => '/pages/category/index',
    ];
    //我的课程
    const STUDY_PAGE        = [
        'mobile'        => '/pages/study/index'
    ];
    //个人中心
    const CENTRE_PAGE       = [
        'mobile'        => '/pages/user/index',
    ];
    //我的订单
    const ORDER_PAGE        = [
        'mobile'        => '/bundle/pages/order_list/index',
    ];
    //我的钱包
    const WALLET_PAGE       = [
        'mobile'        => '/bundle/pages/user_wallet/index',
    ];
    //评论管理
    const EVALUATE_PAGE     = [
        'mobile'        => '/bundle/pages/evaluate_list/index',
    ];
    //我的收藏
    const COLLECTION_PAGE   = [
        'mobile'        => '/bundle/pages/collection_list/index',
    ];
    //个人资料
    const PROFILE_PAGE      = [
        'mobile'        => '/bundle/pages/user_profile/index',
    ];
    //联系客服
    const SERVICE_PAGE      = [
        'mobile'        => '/bundle/pages/contact_service/index',
    ];

    //商城路径页面
    const SHOP_PAGE = [
        [
            'index'     => 1,
            'name'      => '商城首页',
            'path'      => self::HOME_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 2,
            'name'      => '分类页面',
            'path'      => self::CATEGORY_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 3,
            'name'      => '我的课程',
            'path'      => self::STUDY_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 4,
            'name'      => '个人中心',
            'path'      => self::CENTRE_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 5,
            'name'      => '我的订单',
            'path'      => self::ORDER_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 6,
            'name'      => '我的钱包 ',
            'path'      => self::WALLET_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 7,
            'name'      => '评论管理',
            'path'      => self::EVALUATE_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 8,
            'name'      => '我的收藏',
            'path'      => self::COLLECTION_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 9,
            'name'      => '个人资料',
            'path'      => self::PROFILE_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ],
        [
            'index'     => 9,
            'name'      => '联系客服',
            'path'      => self::SERVICE_PAGE['mobile'],
            'params'    => [],
            'type'      => 'shop',
        ]
    ];
}