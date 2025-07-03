<?php
return [
    // 系统版本号
    'version' => '1.6.5',

    // 官网
    'website' => [
        'name' => env('project.web_name', 'likeshop知识付费'), // 网站名称
        'url' => env('project.web_url', 'www.likeshop.cn/'), // 网站地址
        'login_image' => 'resource/image/adminapi/default/login_image.png',
        'web_logo' => 'resource/image/adminapi/default/web_logo.png', // 网站logo
        'web_favicon' => 'resource/image/adminapi/default/web_favicon.ico', // 网站图标
        'shop_name' => 'likeshop知识付费', // 商城名称
        'shop_logo' => 'resource/image/adminapi/default/shop_logo.png', // 商城图标
    ],

    // 后台登录
    'admin_login' => [
        // 管理后台登录限制 0-不限制 1-需要限制
        'login_restrictions' => 1,
        // 限制密码错误次数
        'password_error_times' => 5,
        // 限制禁止多少分钟不能登录
        'limit_login_time' => 30,
    ],

    // 唯一标识，密码盐、路径加密等
    'unique_identification' => env('project.unique_identification', 'likeshop'),

    // 后台管理员token（登录令牌）配置
    'admin_token' => [
        'expire_duration' => 3600 * 8,//管理后台token过期时长(单位秒）
        'be_expire_duration' => 3600,//管理后台token临时过期前时长，自动续期
    ],

    // 商城用户token（登录令牌）配置
    'user_token' => [
        'expire_duration' => 7 * 24 * 60 * 60,//用户token过期时长(单位秒）
        'be_expire_duration' => 3600,//用户token临时过期前时长，自动续期
    ],

    // 列表页
    'lists' => [
        'page_size_max' => 25000,//列表页查询数量限制（列表页每页数量、导出每页数量）
        'page_size' => 25, //默认每页数量
    ],

    // 各种默认图片
    'default_image' => [
        'admin_avatar'      => 'resource/image/adminapi/default/avatar.png',
        'user_avatar'       => 'resource/image/adminapi/default/default_avatar.png',
        'course_text'       => 'resource/image/adminapi/default/text.png',
        'course_video'      => 'resource/image/adminapi/default/video.png',
        'course_audio'      => 'resource/image/adminapi/default/audio.png',
        'teacher'           => 'resource/image/adminapi/default/teacher.png',
        'order'             => 'resource/image/adminapi/default/order.png',
        'subject'           => 'resource/image/adminapi/default/subject.png',
        'summarize'         => 'resource/image/adminapi/default/summarize.png',
        'channel'           => 'resource/image/adminapi/default/channel.png',
    ],

    // 文件上传限制 (图片)
    'file_image' => [
        'jpg', 'png', 'gif', 'jpeg', 'webp'
    ],

    // 文件上传限制 (视频)
    'file_video' => [
        'wmv', 'avi', 'mpg', 'mpeg', '3gp', 'mov', 'mp4', 'flv', 'f4v', 'rmvb', 'mkv'
    ],

    // 登录设置
    'login' => [
        // 登录方式：1-账号密码登录；2-手机短信验证码登录
        'login_way' => ['1', '2'],
        // 注册强制绑定手机 0-关闭 1-开启
        'coerce_mobile' => 1,
        // 第三方授权登录 0-关闭 1-开启
        'third_auth' => 1,
        // 微信授权登录 0-关闭 1-开启
        'wechat_auth' => 1,
        // qq授权登录 0-关闭 1-开启
        'qq_auth' => 0,
        // 登录政策协议 0-关闭 1-开启
        'login_agreement' => 1,
    ],

    // 后台装修
    'decorate' => [
        // 底部导航栏样式设置
        'tabbar_style' => ['default_color' => '#999999', 'selected_color' => '#4173ff'],
    ],


    // 产品code
    'product_code' => 'e917f907a14e3a4f2548962236705e61',
    'check_domain' => 'https://server.likeshop.cn',


    //分销设置
    'distribution_config' => [
        'is_distribution' => 1,//分销功能：1-开启；0-关闭；
        'distribution_level' => 1,//分销层级：1-一级分销；2-二级分销；
        'self_buy' => 0,//自购返佣：1-开启；0-关闭；
        'goods_detail' => 1,//商品详情显示佣金：1-开启；0-关闭；
        'goods_detail_user' => 2,//商品详情页佣金可见用户：1-全部用户；2-分销商；
        'distribution_apply' => 1,//开通分销会员条件：1-无条件；2-申请分销；3-制定分销；
        'apply_image' => 'resource/image/adminapi/default/apply_image.png',//申请页宣传图
        'is_apply_protocol' => 1,//申请协议：1-显示；0-隐藏；
        'calculation_method' => 1,//佣金计算方式：1-商品实际支付金额
        'settlement_time' => 1,//结算时机：1-订单完成后；
        'settlement_time_day' => 7,//结算天数；
    ]

];
