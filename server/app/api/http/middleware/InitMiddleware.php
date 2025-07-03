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
declare (strict_types=1);

namespace app\api\http\middleware;


use app\api\controller\BaseApiController;
use app\common\exception\ControllerExtendException;
use app\common\service\JsonService;
use app\api\controller\BaseShopController;
use think\exception\ClassNotFoundException;
use think\exception\HttpException;

class InitMiddleware
{
    /**
     * @notes 初始化
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @author 令狐冲
     * @date 2021/7/2 19:29
     */
    public function handle($request, \Closure $next)
    {
        //接口版本判断
        $version = $request->header('version');

        if (empty($version) && !$this->nocheck($request)) {
            // 指定show为0，前端不弹出此报错
            return JsonService::fail('请求参数缺少接口版本号', [], 0, 0);
        }

        //获取控制器
        try {
            $controller = str_replace('.', '\\', $request->controller());
            $controller = '\\app\\api\\controller\\' . $controller . 'Controller';
            $controllerClass = invoke($controller);
            if (($controllerClass instanceof BaseApiController) === false) {
                throw new ControllerExtendException($controller, '404');
            }
        } catch (ClassNotFoundException $e) {
            throw new HttpException(404, 'controller not exists:' . $e->getClass());
        }
        //创建控制器对象
        $request->controllerObject = invoke($controller);

        return $next($request);
    }


    /**
     * @notes 是否验证版本号
     * @param $request
     * @return bool
     * @author 段誉
     * @date 2021/9/7 11:37
     */
    public function nocheck($request)
    {
        //特殊方法不验证版本号参数
        $noCheck = [
            'Pay/notifyMnp',
            'Pay/notifyOa',
            'Pay/notifyApp',
            'Pay/aliNotify',
            'Pay/toutiaoNotify'
        ];
        $requestAction = $request->controller() . '/'. $request->action();
        return in_array($requestAction, $noCheck);
    }

}