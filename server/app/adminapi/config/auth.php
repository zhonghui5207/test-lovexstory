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


/**
 * 权限
 */
return [
    // 首页
    'index' => [
        //控制台
        'index' => [
            'page_path' => '/workbench',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['workbench/index'],
            ],
        ]
    ],
    //课程
    'course'    => [
        'index' => [
            'page_path' => [
                '/course/imageText',
                '/course/audio',
                '/course/video',
            ],
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'adminapi/course.course/lists',
                    'course.coursecategory/levelByList',
                ],
            ],
            'manage'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'course.course/otherLists',
                    'course.course/add',
                    'course.course/edit',
                    'course.course/del',
                    'course.course/status',
                    'course.coursecatalogue/lists',
                    'course.coursecatalogue/add',
                    'course.coursecatalogue/edit',
                    'course.coursecatalogue/del',
                    'course.coursecatalogue/detail',
                    'course.coursecatalogue/changeFeeType',
                    'course.coursematerial/detail',
                    'course.coursematerial/set',
                    'course.coursecolumn/lists',
                    'course.coursecolumn/add',
                    'course.coursecolumn/changeFeeType',
                    'course.coursecolumn/del',
                ]
            ],
        ],
        //分类
        'category'  => [
            'page_path' => '/course/category',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'course.coursecategory/lists'
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'course.coursecategory/add',
                    'course.coursecategory/edit',
                    'course.coursecategory/detail',
                    'course.coursecategory/del',
                    'course.coursecategory/status',

                ],
            ],
        ],
        //评价
        'evaluate'  => [
            'page_path' => '/course/evaluate',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'course.courseComment/lists'
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'course.courseComment/reply',
                    'course.courseComment/del',
                ],
            ],
        ],
    ],
    //用户
    'user' => [
        //用户列表
        'index' => [
            'page_path' => '/user/index',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'user.user/lists',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'user.user/detail',
                    'user.user/setInfo',
                    'user.user/adjustUserWallet',
                    'user.user/recycleCourse',
                ],
            ],
        ]
    ],
    //讲师
    'teacher' => [
        //讲师列表
        'index' => [
            'page_path' => '/teacher/index',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'teacher.teacher/lists',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'teacher.teacher/add',
                    'teacher.teacher/edit',
                    'teacher.teacher/detail',
                    'teacher.teacher/del',
                    'teacher.teacher/status',
                ],
            ],
        ]
    ],
    //订单
    'order' => [
        //订单列表
        'index' => [
            'page_path' => '/order/index',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'order.order/lists',
                    'order.order/otherLists',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'order.order/detail',
                    'order.order/shopRemark',
                    'order.order/cancel',
                    'order.order/refund',
                ],
            ],
        ]
    ],
    //装修管理
    'decorate' => [
        //页面装修、底部导航栏
        'decorate' => [
            'page_path' => '/decorate/decorate',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'decorate.decoratepage/detail',
                    'decorate.decoratepage/getShopPage',
                    'decorate.subject/lists',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'decorate.decoratepage/save',
                ],
            ],
        ],
        //精选课程
        'choice' => [
            'page_path' => '/decorate/handpick_course/detail',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'course.course/commonLists',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'course.course/choice',
                ],
            ],
        ]

    ],
    //营销应用
    'application'   => [
        //专题活动
        'decorate' => [
            'page_path' => '/application/subject',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'decorate.subject/lissts',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'decorate.subject/otherLists',
                    'decorate.subject/add',
                    'decorate.subject/edit',
                    'decorate.subject/detail',
                    'decorate.subject/del',
                ],
            ],
        ],
        //用户充值
        'recharge' => [
            'page_path' => '/admin/user_charge',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'recharge.recharge/getRechargeConfig',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'recharge.recharge/setRechargeConfig',
                ],
            ],
        ],
        //通知设置
        'notification' => [
            'page_path' => '/notification/index',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'notice.notice/settingLists',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'notice.notice/detail',
                    'notice.notice/set',
                ],
            ],
        ],
        //短信设置
        'sms' => [
            'page_path' => '/notification/index',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'notice.sms_config/getConfig',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'notice.sms_config/detail',
                    'notice.sms_config/setConfig',
                ],
            ],
        ],
    ],
    //财务管理
    'finance'   => [
        //财务概况
        'summarize' => [
            'page_path' => '/finance/summarize',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'finance.finance/dataCenter',
                ],
            ]
        ],
        //充值记录
        'rechargerecord' => [
            'page_path' => '/finance/charge_record',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'recharge.recharge/lists',
                ],
            ],
        ],
        //余额明细
        'balance' => [
            'page_path' => '/finance/balance_statement',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'accountLog/lists',
                    'accountLog/otherLists',
                ],
            ],
        ],
    ],
    //渠道设置
    'channel'   => [
        //渠道设置
        'mpwechat' => [
            'page_path' => '/channel/mp_wechat/index',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.officialaccountsetting/getConfig',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.officialaccountsetting/setConfig',
                ],
            ],
        ],
        //菜单管理
        'mpwechatmenu' => [
            'page_path' => '/channel/mp_wechat/menu',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.officialaccountmenu/detail',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.officialaccountmenu/saveAndPublish',
                ],
            ],
        ],
        //回复
        'mpwechatreply' => [
            'page_path' => [
                '/channel/mp_wechat/reply/follow_reply',
                '/channel/mp_wechat/reply/default_reply',
                '/channel/mp_wechat/reply/keyword_reply',
                ],
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.officialaccountreply/lists',
                ],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.official_account_reply/add',
                    'channel.official_account_reply/status',
                    'channel.official_account_reply/edit',
                ],
            ],
        ],
        //微信小程序
        'wechatapp' => [
            'page_path' => '/channel/wechat_app',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.mnp_settings/getConfig',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.mnp_settings/setConfig',
                ],
            ],
        ],
        //app
        'appstore' => [
            'page_path' => '/channel/app_store',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.appsetting/getConfig',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.appsetting/setConfig',
                ],
            ],
        ],
        //H5
        'h5store' => [
            'page_path' => '/channel/h5_store',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.h5setting/getConfig',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.h5setting/setConfig',
                ],
            ],
        ],
        //微信开放平台
        'wechatplatform' => [
            'page_path' => '/wechat/wechatplatform',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => [
                    'channel.opensetting/getConfig',
                ],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'channel.opensetting/setConfig',
                ],
            ],
        ],
    ],
    // 权限管理
    'auth' => [
        //管理员
        'permissions' => [
            'page_path' => '/permission/admin',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['auth.admin/lists', 'auth.role/lists'],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'auth.admin/add',
                    'auth.admin/edit',
                    'auth.admin/detail',
                    'auth.admin/del',
                ],
            ],
        ],
        //角色
        'role' => [
            'page_path' => '/permission/role',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['auth.role/lists'],
            ],
            'manage' => [
                'button_auth' => ['auth_all'],
                'action_auth' => [
                    'auth.role/add',
                    'auth.role/edit',
                    'auth.role/detail',
                    'auth.role/del',
                    'config/getMenu',
                ],
            ],
        ],
    ],
    // 系统设置
    'setting' => [
        // 网站信息
        'website' => [
            'page_path' => '/setting/website/information',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.web.websetting/getwebsite'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.web.websetting/setwebsite'],
            ],
        ],
        //备案信息
        'record' => [
            'page_path' => '/setting/website/filing',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.web.websetting/getcopyright'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.web.websetting/setcopyright'],
            ],
        ],
        //政策协议
        'protocol'  => [
            'page_path' => '/setting/website/protocol',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.web.websetting/getAgreement'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.web.websetting/setAgreement'],
            ],
        ],
        //交易设置
        'getConfig'  => [
            'page_path' => '/setting/order',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.transactionsettings/getConfig'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.transactionsettings/setConfig'],
            ],
        ],
        //客服设置
        'service'  => [
            'page_path' => '/setting/service',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.customerservice/getConfig'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.customerservice/setConfig'],
            ],
        ],
        //支付配置
        'paymentconfig'  => [
            'page_path' => '/setting/paymentconfig',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.customerservice/getConfig'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.customerservice/setConfig'],
            ],
        ],
        //支付配置
        'paymentway'  => [
            'page_path' => '/setting/paymentconfig',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.pay.payway/getConfig'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.pay.payway/setConfig'],
            ],
        ],
        //用户设置
        'user'  => [
            'page_path' => '/setting/user',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.user.user/getConfig'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.user.user/setConfig'],
            ],
        ],
        //登录
        'login'  => [
            'page_path' => '/user/login',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.user.user/getRegisterConfig'],
            ],
            'save' => [
                'button_auth' => ['auth_all'],
                'action_auth' => ['setting.user.user/setRegisterConfig'],
            ],
        ],
        //系统环境
        'environment' => [
            'page_path' => '/setting/system/environment',
            'view' => [
                'button_auth' => ['view'],
                'action_auth' => ['setting.system.system/info'],
            ],
        ],
        // 系统日志
        'systemlog'     => [
            'page_path'     => '/setting/system/journal',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['setting.system.log/lists'],
            ],
        ],
        //系统缓存
        'systemcache'     => [
            'page_path'     => '/setting/system/cache',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => [],
            ],
            'clear'     => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['setting.system.cache/clear'],
            ],
        ],
        //储存设置
        'storage'           => [
            'page_path'     => '/setting/storage/index',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['setting.storage/lists'],
            ],
            'manage'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'setting.storage/change',
                    'setting.storage/detail',
                    'setting.storage/setup'
                ],
            ],
        ],
        // 个人设置
        'selfSetting'     => [
            'page_path'     => '/setting/personal/personal_data',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['auth.admin/myself'],
            ],
            'save'     => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['auth.admin/editself'],
            ],
        ],

    ],

];