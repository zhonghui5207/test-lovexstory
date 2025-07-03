<?php
/**
 * name:菜单、权限名称
 * type：类型：1-菜单；2-权限
 * sons:子级菜单
 * ----auth_key：权限key(必须唯一)
 */
return [
    // 首页-工作台
    [
        'name' => '工作台',
        'type' => 1,
        'sons'    => [
            [
                'name'      => '查看',
                'type'      => 2,
                'auth_key'  => 'index/index.view'
            ]
        ],
    ],
    //课程管理
    [
        'name'      => '课程管理',
        'type'      => 1,
        'sons'      => [
            //图文课程
            [
                'name' => '图文、音频、视频、专栏课程',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'course/index.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'course/index.manage'
                    ],
                ],
            ],
            //课程分类
            [
                'name' => '课程分类',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'course/category.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'course/category.manage'
                    ],
                ],
            ],
            //课程评论
            [
                'name' => '课程评价',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'course/evaluate.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'course/evaluate.manage'
                    ],
                ],
            ],
        ],
    ],
    //用户管理
    [

        'name'      => '用户管理',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '用户列表',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'user/index.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'user/index.manage'
                    ],
                ],
            ],
        ]

    ],
    //讲师管理
    [

        'name'      => '讲师管理',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '讲师列表',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'teacher/index.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'teacher/index.manage'
                    ],
                ],
            ],
        ]

    ],
    //订单管理
    [

        'name'      => '订单管理',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '订单列表',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'order/index.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'order/index.manage'
                    ],
                ],
            ],
        ]

    ],
    //装修管理
    [

        'name'      => '装修管理',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '页面装修、底部导航栏',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'decorate/decorate.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'decorate/decorate.save'
                    ],
                ],
            ],
            [
                'name' => '精选课程',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'decorate/choice.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'decorate/choice.manage'
                    ],
                ],
            ],
        ]

    ],
    //专题活动
    [

        'name'      => '营销应用',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '专题活动',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'application/subject.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'application/subject.manage'
                    ],
                ],
            ],
            [
                'name' => '用户充值',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'application/recharge.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'application/recharge.save'
                    ],
                ],
            ],
            [
                'name' => '通知设置',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'application/notification.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'application/notification.manage'
                    ],
                ],
            ],
            [
                'name' => '短信设置',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'application/sms.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'application/sms.manage'
                    ],
                ],
            ],
        ]

    ],
    //财务管理
    [

        'name'      => '财务管理',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '财务概况',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'finance/summarize.view'
                    ]
                ],
            ],
            [
                'name' => '充值记录',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'finance/rechargerecord.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'finance/rechargerecord.manage'
                    ],
                ],
            ],
            [
                'name' => '余额明细',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'finance/balance.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'finance/balance.manage'
                    ],
                ],
            ],
        ]

    ],
    //渠道设置
    [
        'name'      => '渠道设置',
        'type'      => 1,
        'sons'      => [
            [
                'name' => '渠道设置',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'channel/mpwechat.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'channel/mpwechat.save'
                    ],
                ],
            ],
            [
                'name' => '菜单管理',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'finance/mpwechatmenu.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'finance/mpwechatmenu.save'
                    ],
                ],
            ],
            [
                'name' => '关注回复、关键字回复、默认回复',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'inance/mpwechatreply.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'inance/mpwechatreply.manage'
                    ],
                ],
            ],
            [
                'name' => '微信小程序',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'inance/wechatapp.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'inance/wechatapp.save'
                    ],
                ],
            ],
            [
                'name' => 'APP',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'inance/appstore.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'inance/appstore.save'
                    ],
                ],
            ],
            [
                'name' => 'H5',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'inance/h5store.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'inance/h5store.save'
                    ],
                ],
            ],
            [
                'name' => '微信开发平台',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'inance/wechatplatform.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'inance/wechatplatform.save'
                    ],
                ],
            ],
        ]


    ],
    // 权限管理
    [
        'name' => '权限管理',
        'type' => 1,
        'sons' => [
            [
                'name' => '管理员',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'auth/permissions.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'auth/permissions.manage'
                    ],
                ],
            ],
            [
                'name' => '角色',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'auth/role.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'auth/role.manage'
                    ],
                ]
            ],
        ],
    ],
    // 系统设置
    [
        'name' => '系统设置',
        'type' => 1,
        'sons' => [
            //网站信息
            [
                'name' => '网站信息',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/website.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/website.save'
                    ],
                ],
            ],
            //备案信息
            [
                'name' => '备案信息',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/filing.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/filing.save'
                    ],
                ],
            ],
            //政策协议
            [
                'name' => '政策协议',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/protocol.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/protocol.save'
                    ],
                ],
            ],
            //交易设置
            [
                'name' => '交易设置',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/order.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/order.save'
                    ],
                ],
            ],
            //客服设置
            [
                'name' => '客服设置',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/service.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/service.save'
                    ],
                ],
            ],
            //支付配置
            [
                'name' => '支付配置',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/paymentconfig.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/paymentconfig.manage'
                    ],
                ],
            ],
            //支付方式
            [
                'name' => '支付方式',
                'type' => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/paymentway.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/paymentway.manage'
                    ],
                ],
            ],
            //系统日志
            [
                'name'  => '系统日志',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/systemlog.view'
                    ],
                ],
            ],
            //系统缓存
            [
                'name'  => '系统缓存',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/systemcache.view'
                    ],
                    [
                        'name'      => '清除系统缓存',
                        'type'      => 2,
                        'auth_key'  => 'setting/systemcache.clear'
                    ],
                ],
            ],
            //存储设置
            [
                'name'  => '存储设置',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/storage.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/storage.manage'
                    ],
                ],
            ],
            //用户设置
            [
                'name'  => '用户设置',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/user.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/user.save'
                    ],
                ],
            ],
            //登录注册
            [
                'name'  => '登录注册',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/login.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/login.save'
                    ],
                ],
            ],
            //系统环境
            [
                'name'  => '系统环境',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/environment.view'
                    ],
                ],
            ],
            //定时任务
            [
                'name'  => '系统环境',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/task.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/task.manage'
                    ],
                ],
            ],
        ],
    ]
];



