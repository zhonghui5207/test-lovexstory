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

use app\adminapi\http\middleware\AuthMiddleware;
use app\adminapi\http\middleware\CheckDemoMiddleware;
use app\adminapi\http\middleware\EncryDemoDataMiddleware;
use app\adminapi\http\middleware\InitMiddleware;
use app\adminapi\http\middleware\LoginMiddleware;

return [
    'middleware' => [
        // 初始化
        app\adminapi\http\middleware\InitMiddleware::class,
        // 登录验证
        app\adminapi\http\middleware\LoginMiddleware::class,
        // 权限认证
        app\adminapi\http\middleware\AuthMiddleware::class,
        // 演示模式 - 禁止提交数据
        app\adminapi\http\middleware\CheckDemoMiddleware::class,
        // 演示模式 - 不返回敏感数据
        app\adminapi\http\middleware\EncryDemoDataMiddleware::class,
    ],
];

