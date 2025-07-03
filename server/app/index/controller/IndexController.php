<?php
namespace app\index\controller;

use app\BaseController;
use app\common\service\JsonService;

class IndexController extends BaseController
{
    public function index($name = '你好,likeadmin')
    {
        $template = app()->getRootPath() . 'public/mobile/index.html';
        if (file_exists($template)) {
            return view($template);
        }
        return '敬请期待';
    }
}
